<script setup>
import { Form, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PillButton from "../../Components/Ui/PillButton.vue";
import HomeLayout from "../../Layouts/HomeLayout.vue";
import { cn } from "../../lib/utils";

const page = usePage();

const posts = computed(() => page.props.posts);
const q = computed(() => page.props.q);

const links = computed(() => posts.value.links.map((link) => {
    const result = {...link};

    if (result.label === "&laquo; Previous") {
        result.label = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>';
    }

    if (result.label === "Next &raquo;") {
        result.label = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>';
    }

    return result;
}));
</script>

<template>
    <HomeLayout>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
            <div class="lg:w-full lg:max-w-270 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-white">
                <h1 class="font-medium text-2xl">Hasil Pencarian Untuk: {{ q }}</h1>
                <div>
                    <Link :href="route('home')">Beranda</Link>
                    &gt;
                    <Link :href="route('blog.index')">Blog</Link>
                    &gt;
                    Hasil Pencarian Untuk: {{ q }}
                </div>
            </div>
        </section>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-6 lg:flex lg:justify-center">
            <div class="flex flex-col gap-8 lg:w-full lg:max-w-270">
                <Form class="flex flex-col gap-6 lg:w-full lg:max-w-150" :action="route('blog.search')" method="get">
                    <h3 class="font-medium">Cari postingan</h3>
                    <div class="flex items-center gap-4">
                        <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="q" placeholder="Cari postingan" :value="q">
                        <PillButton>Cari</PillButton>
                    </div>
                </Form>
                <div v-if="posts.data.length > 0" class="flex flex-col gap-6">
                    <template v-for="post in posts.data" :key="post.id">
                        <Link :href="route('blog.show', post.slug)" class="flex flex-col gap-6">
                            <h3 class="font-medium">{{ post.title }}</h3>
                            <p class="text-onyx-400">{{ post.excerpt }}</p>
                        </Link>
                        <span class="h-px bg-onyx-200 last:hidden" />
                    </template>
                </div>
                <p v-else>Postingan tidak ditemukan.</p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <template v-for="link in links" :key="link.label">
                        <Link v-if="link.url" :class="cn('w-10 h-10 flex justify-center items-center ring ring-inset ring-onyx-200 text-onyx-400 font-medium', link.active && 'text-primary')" :href="link.url" v-html="link.label" />
                        <span v-else class="w-10 h-10 flex justify-center items-center ring ring-inset ring-onyx-200 text-onyx-400 font-medium" v-html="link.label" />
                    </template>
                </div>
            </div>
        </section>
    </HomeLayout>
</template>
