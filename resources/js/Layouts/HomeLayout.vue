<script setup>
import Footer from "../Components/Footer.vue";
import Header from "../Components/Header.vue";
</script>

<template>
    <div class="font-fira-sans">
        <Header />
        <slot />
        <Footer />
    </div>
</template>
