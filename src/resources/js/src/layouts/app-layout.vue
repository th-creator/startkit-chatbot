<template>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="relative">
      <router-view></router-view>
    </div>
</template>
<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import Sidebar from '@/components/layout/Sidebar.vue';
    import Header from '@/components/layout/Header.vue';
    import Footer from '@/components/layout/Footer.vue';
    import Setting from '@/components/ThemeCustomizer.vue';
    import appSetting from '@/app-setting';

    import { useAppStore } from '@/stores/index';
    const store = useAppStore();
    const showTopButton = ref(false);
    onMounted(() => {
        window.onscroll = () => {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                showTopButton.value = true;
            } else {
                showTopButton.value = false;
            }
        };

        // const eleanimation: any = document.querySelector('.animation');
        // eleanimation.addEventListener('animationend', function () {
        //     appSetting.changeAnimation('remove');
        // });
        store.toggleMainLoader();
    });

    const goToTop = () => {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    };
</script>
