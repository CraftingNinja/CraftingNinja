import { ref } from "vue";
import { debounce } from "lodash";

const items = ref({ data: [], meta: { links: [] } });
const loading = ref(true);
const page = ref(1);

const search = debounce((refinements, newPage) => {
    loading.value = true;
    axios.post(route('api.items.search'), {
            ...refinements.value,
            page: newPage || 1
        })
        .then((response) => items.value = response.data)
        .finally(() => loading.value = false);
}, 150);

export default function () {
    return {
        items,
        loading,
        search,
        page
    };
}
