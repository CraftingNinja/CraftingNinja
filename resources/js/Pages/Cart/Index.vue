<template>
    <Page title="Your List">
        <div class="flex gap-4">
            <ItemList />
            <Options />
        </div>
    </Page>
</template>

<script setup>
import Page from "@/Layouts/Page.vue";
import ItemList from "./Partials/ItemList.vue";
import Options from "./Partials/Options.vue";
</script>
