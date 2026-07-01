<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();

const appUrl = computed(() => page.props.appUrl);
const storageUrl = computed(() => page.props.storageUrl);
const settings = computed(() => page.props.settings);
const user = computed(() => page.props.auth?.user);

const mobileNavbarVisible = ref(false);

const scrollToSection = (e, id, url) => {
    e.preventDefault();
    const el = document.getElementById(id);
    if (el) {
        const headerOffset = 130; 
        const elementPosition = el.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.scrollY - headerOffset;
        
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
        
        mobileNavbarVisible.value = false;
        
        if (window.history.pushState) {
            window.history.pushState(null, null, '#' + id);
        }
    } else {
        router.visit(url);
    }
};
</script>

<template>
    <header class="lg:hidden h-14 sticky top-0 z-1 flex justify-between items-center pr-5 bg-white shadow-[0_-0.0625rem_0_var(--color-onyx-200)_inset]">
        <div class="h-full flex">
            <button class="h-full aspect-square flex justify-center items-center shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset]" @click="mobileNavbarVisible = !mobileNavbarVisible">
                <svg v-if="mobileNavbarVisible" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
            </button>
            <Link :href="route('home')" class="h-full px-8 flex justify-center items-center shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset]">
                <img :src="settings.community_logo ? `${storageUrl}/${settings.community_logo}` : `${appUrl}/images/community-logo.svg`" alt="Logo" width="48">
            </Link>
        </div>
        <Link v-if="user" class="px-6 py-2 rounded-full ring ring-inset ring-black font-medium" :href="route('dashboard')">Dashboard</Link>
        <Link v-else class="px-6 py-2 rounded-full ring ring-inset ring-black font-medium" :href="route('login')">Login</Link>
        <nav v-if="mobileNavbarVisible" class="absolute top-full left-0 w-full flex flex-col bg-white">
            <a class="p-4 font-medium shadow-[0_-0.0625rem_0_var(--color-onyx-200)_inset] cursor-pointer" :href="`${route('home')}#about`" @click="scrollToSection($event, 'about', `${route('home')}#about`)">TENTANG</a>
            <Link class="p-4 font-medium shadow-[0_-0.0625rem_0_var(--color-onyx-200)_inset]" :href="route('blog.index')">BLOG</Link>
            <a class="p-4 font-medium shadow-[0_-0.0625rem_0_var(--color-onyx-200)_inset] cursor-pointer" :href="`${route('home')}#contact`" @click="scrollToSection($event, 'contact', `${route('home')}#contact`)">KONTAK</a>
            <div class="h-14 flex shadow-[0_-0.0625rem_0_var(--color-onyx-200)_inset]">
                <a class="w-full h-full flex justify-center items-center shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset]" :href="settings.email ? `mailto:${settings.email}` : '#'" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                </a>
                <a class="w-full h-full flex justify-center items-center shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset]" :href="settings.phone ? `tel:${settings.phone}` : '#'" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-icon lucide-phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                </a>
                <a class="w-full h-full flex justify-center items-center shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset]" :href="settings.social_youtube || '#'" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/></svg>
                </a>
                <a class="w-full h-full flex justify-center items-center" :href="settings.social_instagram || '#'" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg>
                </a>
            </div>
        </nav>
    </header>
    <header class="hidden lg:block sticky top-0 z-1">
        <nav class="flex flex-col">
            <div class="h-14 flex justify-center px-4 bg-white">
                <div class="w-full max-w-270 h-full flex justify-between">
                    <Link class="flex items-center px-8 shadow-[0.0625rem_0_0_var(--color-onyx-200)_inset,-0.0625rem_0_0_var(--color-onyx-200)_inset]" :href="route('home')">
                        <img :src="settings.community_logo ? `${storageUrl}/${settings.community_logo}` : `${appUrl}/images/community-logo.svg`" alt="Logo" width="48">
                    </Link>
                    <div class="flex">
                        <a class="w-full h-full flex items-center gap-3 px-4 font-medium shadow-[0.0625rem_0_0_var(--color-onyx-200)_inset,-0.0625rem_0_0_var(--color-onyx-200)_inset] whitespace-nowrap" :href="settings.email ? `mailto:${settings.email}` : '#'" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                            {{ settings.email || "-" }}
                        </a>
                        <a class="w-full h-full flex items-center gap-3 px-4 font-medium shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset] whitespace-nowrap" :href="settings.phone ? `tel:${settings.phone}` : '#'" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-icon lucide-phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                            {{ settings.phone || "-" }}
                        </a>
                        <a class="w-full h-full flex items-center gap-3 px-4 font-medium shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset] whitespace-nowrap" :href="settings.social_youtube || '#'" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/></svg>
                            YouTube
                        </a>
                        <a class="w-full h-full flex items-center gap-3 px-4 font-medium shadow-[-0.0625rem_0_0_var(--color-onyx-200)_inset] whitespace-nowrap" :href="settings.social_instagram || '#'" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg>
                            Instagram
                        </a>
                    </div>
                </div>
            </div>
            <div class="relative h-14 flex justify-center items-center gap-8 px-4 bg-[rgba(0,0,0,0.2)]">
                <div class="absolute top-0 left-0 w-full h-full bg-primary -z-1" />
                <a class="font-medium text-white cursor-pointer" :href="`${route('home')}#about`" @click="scrollToSection($event, 'about', `${route('home')}#about`)">TENTANG</a>
                <Link class="font-medium text-white" :href="route('blog.index')">BLOG</Link>
                <a class="font-medium text-white cursor-pointer" :href="`${route('home')}#contact`" @click="scrollToSection($event, 'contact', `${route('home')}#contact`)">KONTAK</a>
                <Link v-if="user" class="px-6 py-2 rounded-full ring ring-inset ring-white font-medium text-white" :href="route('dashboard')">Dashboard</Link>
                <Link v-else class="px-6 py-2 rounded-full ring ring-inset ring-white font-medium text-white" :href="route('login')">Login</Link>
            </div>
        </nav>
    </header>
</template>
