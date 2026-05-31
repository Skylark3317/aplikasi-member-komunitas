<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PostCategory from "../../Enums/PostCategory.js";
import HomeLayout from "../../Layouts/HomeLayout.vue";

const page = usePage();

const post = computed(() => page.props.post);
const category = computed(() => PostCategory.from(post.value?.category?.slug));
</script>

<template>
    <HomeLayout>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
            <div class="lg:w-full lg:max-w-270 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-white">
                <h1 class="font-medium text-2xl">Blog</h1>
                <div>
                    <Link :href="route('home')">Beranda</Link>
                    &gt;
                    <Link :href="route('blog.index')">Blog</Link>
                    &gt;
                    <template v-if="post">
                        <Link :href="route('blog.index', { _query: { category: category.value } })">
                            {{ category.label }}
                        </Link>
                        &gt;
                        {{ post.title }}
                    </template>
                    <template v-else>
                        Postingan tidak ditemukan.
                    </template>
                </div>
            </div>
        </section>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-6 lg:flex lg:justify-center">
            <div class="flex flex-col gap-8 w-full lg:max-w-270 lg:grid lg:grid-cols-3">
                <div v-if="post" class="flex flex-col gap-6 p-6 lg:col-span-2 ring ring-inset ring-onyx-200">
                    <span class="bg-primary text-white text-sm p-1 self-start">{{ post.date }}</span>
                    <h1 class="font-medium text-2xl">{{ post.title }}</h1>
                    <span class="h-px bg-onyx-200" />
                    <div class="text-onyx-400 flex flex-col gap-4" v-html="post.content" />
                    <div class="flex items-center gap-2.5">
                        <div class="shrink-0 w-8 h-8 flex justify-center items-center">
                            <span class="w-6 h-0.5 bg-primary" />
                        </div>
                        <div class="text-onyx-400">
                            oleh
                            <strong class="font-medium">{{ post.author.name }}</strong>
                            dalam
                            <strong class="font-medium">{{ category.label }}</strong>
                        </div>
                    </div>
                </div>
                <p v-else class="w-full lg:max-w-270 lg:col-span-2">Postingan tidak ditemukan.</p>
                <div class="flex flex-col gap-6 p-6 lg:self-start ring ring-inset ring-onyx-200">
                    <h3 class="font-medium">Kategori</h3>
                    <div class="flex flex-col gap-2">
                        <template v-for="[key, c] in PostCategory.entries()" :key="key">
                            <Link class="font-medium text-onyx-400" :href="route('blog.index', { _query: { category: c.value } })">
                                {{ c.label }}
                            </Link>
                            <span class="h-px bg-onyx-200 last:hidden" />
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </HomeLayout>
</template>
