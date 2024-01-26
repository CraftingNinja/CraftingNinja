<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JobController;
use App\Http\Resources\ItemResource as ItemResource;
use App\Models\Item;
use App\Models\Ninja\ListItems;
use App\Models\Ninja\Lists;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class ListsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Lists/Index', [
            'filters' => request()->all('author', 'search', 'sort', 'order'),
            'lists' => Lists::with('user')
                ->withCount('items')
                ->withSum('items', 'quantity')
                ->public()
                ->filter(request()->only('author', 'search', 'sort', 'order'))
                ->paginate(15)
                ->withQueryString()
                ->through(fn ($list) => [
                    'id' => $list->id,
                    'sqid' => $list->sqid,
                    'name' => $list->name,
                    'user' => $list->user,
                    'created_at' => $list->created_at,
                    'item_count' => $list->items_count,
                    'item_quantity' => $list->items_sum_quantity,
                ]),
        ]);
    }

    public function show(Lists $list): Response
    {
        $list->load(['user', 'items' => fn ($query) => $query->orderBy('id')]);

        // If they don't own the list, or the list isn't public, they are unauthorized to view
        abort_if( $list->user->id !== auth()->user()?->id && ! $list->is_public, 403);

        $jobs = (new JobController)->getCraftingJobs();

        return Inertia::render('Lists/Show', compact('list', 'jobs'));
    }

    public function createFromCart(): Response
    {
        $list = [
            'name' => '',
            'description' => '',
            'is_public' => false,
            'items' => request('items', session('createFromCartItems')),
        ];

        abort_if(! $list['items'], 400);

        return Inertia::render('Lists/Form', compact('list'));
    }

    public function store(): RedirectResponse
    {
        // Item Validation
        $items = array_filter(request('items'), fn ($entry) => ! isset($entry['delete']) || ! $entry['delete']);

        abort_if(! $items, 400);

        // Form validation
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'is_public' => 'nullable',
        ]);

        if ($validator->fails()) {
            session(['createFromCartItems' => $items]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $list = Lists::make(
            $validator->validated()
        );

        $list->user()->associate(auth()->user()->id);
        $list->game()->associate(config('game.id'));

        $list->save();

        $list->items()->createMany($items);

        return to_route('lists.show', $list);
    }

    public function edit(Lists $list): Response
    {
        $list->load(['items' => fn ($query) => $query->orderBy('id')]);

        return Inertia::render('Lists/Form', compact('list'));
    }

    public function update(Lists $list): RedirectResponse
    {
        $list->fill(request()->only('name', 'description', 'is_public'));
        $list->save();

        $list->load('items');
        $items = request('items');

        $createMe = $deleteMe = [];
        foreach ($items as $item) {
            $existing = $list->items->where('id', $item['id'])->first();

            if (! $existing) {
                $createMe[] = $item;
            } elseif (isset($item['delete']) && $item['delete']) {
                $deleteMe[] = $item['id'];
            } else {
                // $existing->recipe_id = $item['recipe_id']; // No method ot change this on the frontend; ignore for now
                $existing->quantity = $item['quantity'];
                $existing->save(); // is_dirty state will save us a db call if it's unnecessary
            }
        }
        $list->items()->createMany($createMe);
        $list->items()->whereIn('id', $deleteMe)->delete();

        return to_route('lists.show', $list->id)->with('success', 'List Updated');
    }

    public function destroy(Lists $list): RedirectResponse
    {
        $list->delete();
        return to_route('lists.index')->with('success', 'List Deleted');
    }
}
