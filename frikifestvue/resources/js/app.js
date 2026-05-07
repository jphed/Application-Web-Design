import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
  title: (title) => `${title} - FrikiFest Vue`,
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    const page = pages[`./Pages/${name}.vue`];
    return page?.default ?? page;
  },
  setup({ el, App, props, plugin }) {
    const ziggy = props.initialPage.props.ziggy;

    const app = createApp({ render: () => h(App, props) });
    app.use(plugin);
    if (ziggy) {
      app.use(ZiggyVue, ziggy);
    }
    app.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
