<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="'/'">
      <div class="w-[80px] h-[80px] bg-indigo-50 rounded-md flex items-center justify-center overflow-hidden">
  <img src="/images/ltd.svg" alt="LTD Logo" class="w-full h-full object-contain" />
</div>

    </Link>
</template>
