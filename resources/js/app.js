import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import DialogPlugin from './plugins/dialog';
import AnimationPlugin from './plugins/animations';

const appName = import.meta.env.VITE_APP_NAME || 'AMK';

createInertiaApp({
    title: (title) => {
        let currentAppName = appName;
        
        // Try to get latest settings from Inertia router
        if (router.page && router.page.props && router.page.props.settings && router.page.props.settings.community_name) {
            currentAppName = router.page.props.settings.community_name;
        } else {
            // Fallback for initial page load
            try {
                const el = document.getElementById('app');
                if (el && el.dataset.page) {
                    const pageData = JSON.parse(el.dataset.page);
                    if (pageData.props.settings && pageData.props.settings.community_name) {
                        currentAppName = pageData.props.settings.community_name;
                    }
                }
            } catch (e) {}
        }

        // Clean up hardcoded suffix if it exists
        const cleanTitle = title.replace(' - AMK', '').replace(' - ' + currentAppName, '');
        return cleanTitle ? `${cleanTitle} - ${currentAppName}` : currentAppName;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(DialogPlugin)
            .use(AnimationPlugin)
            .mount(el);
    },
    progress: {
        color: '#2563eb',
    },
});
