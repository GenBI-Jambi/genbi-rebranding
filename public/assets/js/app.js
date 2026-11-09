(function () {
  'use strict';
const fallbackData = window.GenBIData || {};
const { navItems = [], site: fallbackSite = {} } = fallbackData;
const Core = window.GenBIAPICore || {};
const site = mergeSiteSettings(fallbackSite, window.GenBISiteSettings || {});

const HERO_ICONS = {
  menu: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>',
  x: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>',
  mail: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.24a2.25 2.25 0 0 1-1.07 1.92l-7.5 4.62a2.25 2.25 0 0 1-2.36 0l-7.5-4.62a2.25 2.25 0 0 1-1.07-1.92v-.24"/></svg>',
  phone: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.28 6.72 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.37c0-.52-.36-.97-.86-1.1l-4.42-1.1a1.13 1.13 0 0 0-1.17.42l-.97 1.3a1.13 1.13 0 0 1-1.21.39 12.04 12.04 0 0 1-7.15-7.15 1.13 1.13 0 0 1 .39-1.21l1.3-.97c.36-.27.52-.73.42-1.17L6.98 3.61a1.13 1.13 0 0 0-1.1-.86H4.5A2.25 2.25 0 0 0 2.25 5v1.75Z"/></svg>',
  arrowRight: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>',
  arrowUp: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0-6.75 6.75M12 4.5l6.75 6.75"/></svg>',
  chevronRight: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>',
  chevronDown: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>',
  sparkles: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.8 3.7 8.4 8.1 4 9.5l4.4 1.4 1.4 4.4 1.4-4.4 4.4-1.4-4.4-1.4-1.4-4.4Zm7.4 6.7-.9 2.8-2.8.9 2.8.9.9 2.8.9-2.8 2.8-.9-2.8-.9-.9-2.8Z"/></svg>',
  users: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5c0-2.07-2.69-3.75-6-3.75s-6 1.68-6 3.75M9 12.75A3.75 3.75 0 1 0 9 5.25a3.75 3.75 0 0 0 0 7.5Zm12 6.75c0-1.74-1.9-3.2-4.45-3.62M15 5.58a3.75 3.75 0 0 1 0 6.84"/></svg>',
  bank: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 4.5l8.25 5.25M5.25 10.5h13.5M6.75 10.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5M4.5 18h15"/></svg>',
  chart: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 19.5h16.5M6.75 16.5v-6m5.25 6V6.75m5.25 9.75v-9"/></svg>',
  academic: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4.5l8.25 3.75L12 12 3.75 8.25Zm3 2.25v4.25c0 1.66 2.35 3 5.25 3s5.25-1.34 5.25-3V10.5"/></svg>',
  calendar: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A2.25 2.25 0 0 1 18 21H6a2.25 2.25 0 0 1-2.25-2.25v-12A1.5 1.5 0 0 1 5.25 5.25Z"/></svg>',
  heart: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.49-2.01-4.5-4.5-4.5A4.48 4.48 0 0 0 12 6.36a4.48 4.48 0 0 0-4.5-2.61C5.01 3.75 3 5.76 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
  news: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h13.5A1.5 1.5 0 0 1 19.5 8.25v9A2.25 2.25 0 0 0 21.75 15V6.75H19.5m-15 0A1.5 1.5 0 0 0 3 8.25v9A2.25 2.25 0 0 0 5.25 19.5h14.25M7.5 10.5h6M7.5 13.5h6M7.5 16.5h3"/></svg>',
  grid: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Zm-10 10h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Z"/></svg>',
  bolt: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z"/></svg>',
  shield: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75c2.7 2.3 5.26 3.24 7.5 3.75v4.95c0 4.2-2.43 7.23-7.5 9.8-5.07-2.57-7.5-5.6-7.5-9.8V7.5c2.24-.51 4.8-1.45 7.5-3.75Z"/></svg>',
  globe: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3c3.5 3.5 3.5 14.5 0 18m0-18c-3.5 3.5-3.5 14.5 0 18M3.75 7.5h16.5M3.75 16.5h16.5"/></svg>',
  megaphone: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11.25v1.5A2.25 2.25 0 0 0 5.25 15h2.04l1.53 4.06A1.5 1.5 0 0 0 10.22 20h.53a1.5 1.5 0 0 0 1.46-1.83L11.65 15H12l7.5 3.75V5.25L12 9H5.25A2.25 2.25 0 0 0 3 11.25Z"/></svg>',
  lightBulb: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6m-5.25 3h4.5M12 3a6.75 6.75 0 0 0-3.98 12.2c.6.44.98 1.12.98 1.87V18h6v-.93c0-.75.38-1.43.98-1.87A6.75 6.75 0 0 0 12 3Z"/></svg>',
  briefcase: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V6A2.25 2.25 0 0 1 11.25 3.75h1.5A2.25 2.25 0 0 1 15 6v.75m-10.5 0h15A1.5 1.5 0 0 1 21 8.25v8.25A2.25 2.25 0 0 1 18.75 18.75H5.25A2.25 2.25 0 0 1 3 16.5V8.25a1.5 1.5 0 0 1 1.5-1.5Z"/></svg>',
  chatBubble: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10.5h9m-9 3h5.25M21 12c0 4.14-4.03 7.5-9 7.5a10.7 10.7 0 0 1-3.72-.65L3 20.25l1.42-3.79A6.85 6.85 0 0 1 3 12c0-4.14 4.03-7.5 9-7.5s9 3.36 9 7.5Z"/></svg>',
  mapPin: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6-4.35 6-10.2a6 6 0 1 0-12 0C6 16.65 12 21 12 21Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z"/></svg>',
  trophy: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h7.5v2.25A3.75 3.75 0 0 1 12 10.5a3.75 3.75 0 0 1-3.75-3.75V4.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6.75H4.5A1.5 1.5 0 0 0 3 8.25c0 2.49 2.01 4.5 4.5 4.5h.17m10.33-6H19.5A1.5 1.5 0 0 1 21 8.25c0 2.49-2.01 4.5-4.5 4.5h-.17M12 10.5V15m-3 4.5h6"/></svg>',
  rocket: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 3.75c2.65.17 4.83 2.35 5 5L12 16l-7.25-7.25c.17-2.65 2.35-4.83 5-5L12 6l2.25-2.25Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15.75 6 18l-2.25.75L4.5 16.5 6.75 14.25M15.75 8.25a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>',
  building: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M5.25 21V6.75A2.25 2.25 0 0 1 7.5 4.5h9a2.25 2.25 0 0 1 2.25 2.25V21M9 9h.01M12 9h.01M15 9h.01M9 12h.01M12 12h.01M15 12h.01M11.25 21v-3.75h1.5V21"/></svg>',
  document: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 3v4.5a.75.75 0 0 0 .75.75h4.5M6.75 3.75h7.06c.4 0 .78.16 1.06.44l3.94 3.94c.28.28.44.66.44 1.06v10.06A2.25 2.25 0 0 1 17 21.75H6.75A2.25 2.25 0 0 1 4.5 19.5V6A2.25 2.25 0 0 1 6.75 3.75Z"/></svg>',
  clock: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4.5 2.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
  flag: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 21V4.5m0 0h9l-.75 3 3.75 1.5-.75 3h-11.25"/></svg>',
  paperAirplane: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11.25 21 3l-8.25 18-1.5-7.5L3 11.25Z"/></svg>',
  userGroup: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.75c0-1.9-2.69-3.45-6-3.45s-6 1.55-6 3.45M12 12.75A3.75 3.75 0 1 0 12 5.25a3.75 3.75 0 0 0 0 7.5M20.25 18.75c0-1.4-1.2-2.62-3-3.19M16.5 5.58a3.75 3.75 0 0 1 0 6.84"/></svg>',
  qrCode: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h6v6h-6v-6Zm9 0h6v6h-6v-6Zm-9 9h6v6h-6v-6Zm11.25 0h3.75M13.5 15.75h2.25V18m3.75-2.25V19.5H15"/></svg>',
  presentationChart: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v10.5H3.75V4.5Zm8.25 10.5v4.5m-3 0h6M7.5 11.25l2.25-2.25 1.5 1.5 3-3"/></svg>',
  envelopeOpen: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25 12 13.5l9.75-5.25M3.75 19.5h16.5A1.5 1.5 0 0 0 21.75 18V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/></svg>',
  phoneArrowUp: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 6h3.75V9.75M18.75 6l-4.5 4.5M2.25 6.75c0 8.28 6.72 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.37c0-.52-.36-.97-.86-1.1l-4.42-1.1a1.13 1.13 0 0 0-1.17.42l-.97 1.3a1.13 1.13 0 0 1-1.21.39 12.04 12.04 0 0 1-7.15-7.15 1.13 1.13 0 0 1 .39-1.21l1.3-.97c.36-.27.52-.73.42-1.17L6.98 3.61a1.13 1.13 0 0 0-1.1-.86H4.5A2.25 2.25 0 0 0 2.25 5v1.75Z"/></svg>',
  settings: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.6 3.5h4.8l.58 2.32a7.8 7.8 0 0 1 1.7.98l2.27-.68 2.4 4.16-1.7 1.64c.06.35.1.72.1 1.08s-.04.73-.1 1.08l1.7 1.64-2.4 4.16-2.27-.68a7.8 7.8 0 0 1-1.7.98l-.58 2.32H9.6l-.58-2.32a7.8 7.8 0 0 1-1.7-.98l-2.27.68-2.4-4.16 1.7-1.64a6.42 6.42 0 0 1-.1-1.08c0-.36.04-.73.1-1.08l-1.7-1.64 2.4-4.16 2.27.68c.53-.4 1.1-.73 1.7-.98L9.6 3.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"/></svg>',
  page: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.1a2.25 2.25 0 0 0-.66-1.59l-3.4-3.4a2.25 2.25 0 0 0-1.59-.66H6.75A2.25 2.25 0 0 0 4.5 4.75v14.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12h7.5M8.25 15h7.5M8.25 18h4.5M14.25 2.5v4.25c0 .83.67 1.5 1.5 1.5H20"/></svg>',
  event: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A2.25 2.25 0 0 1 18 21H6a2.25 2.25 0 0 1-2.25-2.25v-12A1.5 1.5 0 0 1 5.25 5.25Z"/></svg>',
  slider: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 16.5 4.72-4.72a1.5 1.5 0 0 1 2.12 0l2.16 2.16 1.22-1.22a1.5 1.5 0 0 1 2.12 0l4.16 4.16M8.25 8.25h.01"/></svg>',
  gallery: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75 7.5 10.5a2.25 2.25 0 0 1 3.18 0l1.82 1.82.82-.82a2.25 2.25 0 0 1 3.18 0l5.25 5.25M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Zm12-1.5h.01"/></svg>',
  feature: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 3.75 8.25 12 12.75l8.25-4.5L12 3.75Zm0 9v7.5m0-7.5L3.75 8.25m8.25 4.5 8.25-4.5"/></svg>'
};

Object.assign(HERO_ICONS, {
  photo: HERO_ICONS.slider,
  image: HERO_ICONS.gallery,
  documentText: HERO_ICONS.page,
  newspaper: HERO_ICONS.news,
  squares: HERO_ICONS.feature,
  table: HERO_ICONS.page,
  bars: HERO_ICONS.menu,
  window: HERO_ICONS.page,
  swatch: HERO_ICONS.settings,
  academicCap: HERO_ICONS.academic,
  archiveBox: HERO_ICONS.document,
  arrowTrendingUp: HERO_ICONS.chart,
  atSymbol: HERO_ICONS.envelopeOpen,
  banknotes: HERO_ICONS.bank,
  beaker: HERO_ICONS.lightBulb,
  bookOpen: HERO_ICONS.academic,
  bookmark: HERO_ICONS.flag,
  calendarDays: HERO_ICONS.calendar,
  camera: HERO_ICONS.gallery,
  chartBar: HERO_ICONS.chart,
  chartPie: HERO_ICONS.presentationChart,
  chatBubbleLeftRight: HERO_ICONS.chatBubble,
  clipboardDocument: HERO_ICONS.document,
  cloud: HERO_ICONS.globe,
  codeBracket: HERO_ICONS.document,
  cog6Tooth: HERO_ICONS.settings,
  commandLine: HERO_ICONS.document,
  computerDesktop: HERO_ICONS.presentationChart,
  cpuChip: HERO_ICONS.settings,
  creditCard: HERO_ICONS.bank,
  cube: HERO_ICONS.feature,
  devicePhoneMobile: HERO_ICONS.phoneArrowUp,
  envelope: HERO_ICONS.mail,
  eye: HERO_ICONS.globe,
  film: HERO_ICONS.gallery,
  fire: HERO_ICONS.bolt,
  folder: HERO_ICONS.document,
  gift: HERO_ICONS.heart,
  handRaised: HERO_ICONS.users,
  handThumbUp: HERO_ICONS.heart,
  hashtag: HERO_ICONS.qrCode,
  home: HERO_ICONS.building,
  identification: HERO_ICONS.users,
  inbox: HERO_ICONS.envelopeOpen,
  key: HERO_ICONS.shield,
  link: HERO_ICONS.globe,
  lockClosed: HERO_ICONS.shield,
  magnifyingGlass: HERO_ICONS.globe,
  map: HERO_ICONS.mapPin,
  microphone: HERO_ICONS.megaphone,
  paintBrush: HERO_ICONS.settings,
  pencilSquare: HERO_ICONS.document,
  playCircle: HERO_ICONS.slider,
  printer: HERO_ICONS.document,
  puzzlePiece: HERO_ICONS.feature,
  radio: HERO_ICONS.megaphone,
  receiptPercent: HERO_ICONS.bank,
  rss: HERO_ICONS.globe,
  scale: HERO_ICONS.bank,
  server: HERO_ICONS.settings,
  share: HERO_ICONS.paperAirplane,
  shoppingBag: HERO_ICONS.briefcase,
  speakerWave: HERO_ICONS.megaphone,
  star: HERO_ICONS.sparkles,
  tag: HERO_ICONS.flag,
  ticket: HERO_ICONS.calendar,
  truck: HERO_ICONS.briefcase,
  tv: HERO_ICONS.presentationChart,
  user: HERO_ICONS.users,
  userCircle: HERO_ICONS.users,
  userPlus: HERO_ICONS.userGroup,
  videoCamera: HERO_ICONS.gallery,
  wallet: HERO_ICONS.bank,
  wifi: HERO_ICONS.globe,
});

const _iconsInitialized = {
};

function icon(name, cls = '') {
  const svg = HERO_ICONS[name] || HERO_ICONS.sparkles || '';
  return svg.replace('class="', `class="${cls} `);
}

function navHref(item) {
  if (item.href) return item.href;
  return pageUrl(item.key);
}

function navIsActive(item, activeKey) {
  if (item.key === activeKey) return true;
  return Array.isArray(item.children) && item.children.some((child) => child.key === activeKey);
}

function renderDesktopNavItem(item, activeKey) {
  const active = navIsActive(item, activeKey);
  if (Array.isArray(item.children) && item.children.length) {
    return `
      <div class="nav-dropdown ${active ? 'is-active' : ''}">
        <a data-transition href="${navHref(item)}" class="nav-link nav-dropdown-trigger ${active ? 'nav-link-active' : ''}" aria-haspopup="true" aria-expanded="false">
          ${item.label}${icon('chevronDown')}
        </a>
        <div class="nav-dropdown-menu" role="menu">
          ${item.children.map((child) => `<a data-transition href="${navHref(child)}" role="menuitem" class="${child.key === activeKey ? 'is-active' : ''}">${child.label}</a>`).join('')}
        </div>
      </div>
    `;
  }
  return `<a data-transition href="${navHref(item)}" class="nav-link ${active ? 'nav-link-active' : ''}">${item.label}</a>`;
}

function renderMobileNavItem(item, activeKey) {
  const active = navIsActive(item, activeKey);
  if (Array.isArray(item.children) && item.children.length) {
    return `
      <div class="mobile-link-group">
        <a data-transition href="${navHref(item)}" class="mobile-link ${active ? 'mobile-link-active' : ''}">${item.label}<span class="mobile-link-icon">${icon('chevronRight')}</span></a>
        <div class="mobile-sub-links">
          ${item.children.map((child) => `<a data-transition href="${navHref(child)}" class="mobile-sub-link ${child.key === activeKey ? 'is-active' : ''}">${child.label}</a>`).join('')}
        </div>
      </div>
    `;
  }
  return `<a data-transition href="${navHref(item)}" class="mobile-link ${active ? 'mobile-link-active' : ''}">${item.label}<span class="mobile-link-icon">${icon('chevronRight')}</span></a>`;
}

function renderShell(activeKey = 'home') {
  renderHeader(activeKey);
  renderFooter();
  setupShellInteractions();
}

function setupShellInteractions() {
  setupMobileMenu();
  setupAutoHideHeader();
  setupPageTransitions();
  setupBackToTop();
}

function mergeSiteSettings(base, override) {
  return {
    ...base,
    ...override,
    heroSlides: Array.isArray(override.heroSlides) && override.heroSlides.length ? override.heroSlides : base.heroSlides,
    sidebar: { ...(base.sidebar || {}), ...(override.sidebar || {}) },
    colors: { ...(base.colors || {}), ...(override.colors || {}) },
  };
}

function renderHeader(activeKey) {
  const header = document.querySelector('#site-header');
  if (!header) return;
  if (document.body.dataset.ssr === 'true' && header.querySelector('#site-header-shell')) {
    return;
  }

  const nav = navItems
    .map((item) => renderDesktopNavItem(item, activeKey))
    .join('');

  header.innerHTML = `
    <div id="site-header-shell" class="site-header-shell">
      <div class="top-strip hidden md:block">
        <div class="site-container flex h-9 items-center justify-between text-[13px] text-white/90">
          <div class="flex items-center gap-4">
            <a href="mailto:${site.email}" class="inline-flex items-center gap-2 hover:text-white">${icon('mail')}${site.email}</a>
            <span class="h-4 w-px bg-white/30"></span>
            <a href="tel:${site.phone}" class="inline-flex items-center gap-2 hover:text-white">${icon('phone')}${site.phone}</a>
          </div>
          <div class="flex items-center gap-3" aria-label="Social links">
            <!-- Facebook -->
            <a href="https://facebook.com/genbijambi" class="social-mini" aria-label="Facebook">
              <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <!-- Instagram -->
            <a href="https://www.instagram.com/genbi_jambi/" class="social-mini" aria-label="Instagram">
              <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
            </a>
            <!-- YouTube -->
            <a href="https://youtube.com/@genbijambi" class="social-mini" aria-label="YouTube">
              <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
            <!-- WhatsApp -->
            <a href="https://wa.me/6285669152702" class="social-mini" aria-label="WhatsApp">
              <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 1.766 6.26L0 24l5.908-1.765A11.96 11.96 0 0 0 11.944 24c6.627 0 12-5.373 12-12s-5.373-12-12-12zM12 22a9.95 9.95 0 0 1-5.07-1.39l-.36-.22-3.77 1.12 1.01-3.66-.23-.37A9.97 9.97 0 0 1 2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10zm5.48-7.58c-.3-.15-1.78-.88-2.06-.98-.28-.1-.49-.15-.7.15-.2.3-.8.98-.98 1.18-.18.2-.36.22-.66.07-.3-.15-1.28-.47-2.43-1.5-.9-.8-1.51-1.78-1.69-2.08-.18-.3-.02-.46.13-.61.13-.13.3-.34.45-.5.15-.15.2-.26.3-.43.1-.17.05-.32-.02-.47-.07-.15-.65-1.57-.9-2.16-.24-.58-.49-.5-.67-.51-.17 0-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.87 1.22 3.07.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.58-.09 1.78-.73 2.03-1.44.25-.71.25-1.32.18-1.44-.07-.12-.27-.2-.57-.35z"/></svg>
            </a>
          </div>
        </div>
      </div>
      <header class="site-main-header border-b border-neutral-900/10 bg-[rgba(251,250,247,0.92)] backdrop-blur-xl">
        <div class="site-container flex h-20 items-center justify-between">
          <a data-transition href="${pageUrl('home')}" class="flex items-center gap-3" aria-label="Go to home">
            <span class="logo-shell"><img src="${site.logo}" alt="${site.name}" class="h-9 w-auto" /></span>
            <span class="leading-tight">
              <span class="block text-[15px] font-semibold tracking-tight text-neutral-950">GenBI</span>
              <span class="block text-xs font-medium text-blue-800">Provinsi Jambi</span>
            </span>
          </a>
          <nav class="hidden items-center gap-1 lg:flex" aria-label="Primary navigation">
            ${nav}
          </nav>
          <div class="hidden items-center gap-3 lg:flex shrink-0">
            <a data-transition href="${adminUrl('dashboard')}" class="btn btn-secondary">Pratinjau Admin</a>
            <a data-transition href="${pageUrl('contact')}" class="btn btn-primary">Hubungi Kami ${icon('arrowRight')}</a>
          </div>
          <div class="lg:hidden flex items-center">
            <button id="open-menu" class="btn-icon" aria-label="Open menu">${icon('menu')}</button>
          </div>
        </div>
      </header>
    </div>
    <div id="site-header-spacer" aria-hidden="true"></div>
    <div id="mobile-panel" class="fixed inset-0 z-[70] hidden bg-neutral-950/35 backdrop-blur-sm lg:hidden">
      <div class="mobile-sheet">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <span class="logo-shell"><img src="${site.logo}" alt="${site.name}" class="h-8 w-auto" /></span>
            <span class="font-semibold text-neutral-950">Menu</span>
          </div>
          <button id="close-menu" class="btn-icon" aria-label="Close menu">${icon('x')}</button>
        </div>
        <nav class="mt-8 grid gap-2" aria-label="Mobile navigation">
          ${navItems
            .map((item) => renderMobileNavItem(item, activeKey))
            .join('')}
          <a data-transition href="${adminUrl('dashboard')}" class="mobile-link">Pratinjau Admin<span class="mobile-link-icon">${icon('chevronRight')}</span></a>
        </nav>
        <div class="mt-8 rounded-2xl bg-blue-50 p-4 text-sm leading-6 text-blue-950">
          <strong>${site.name}</strong><br />${site.tagline}
        </div>
      </div>
    </div>
  `;
}

function renderFooter() {
  const footer = document.querySelector('#site-footer');
  if (!footer) return;
  if (document.body.dataset.ssr === 'true' && footer.querySelector('section')) {
    return;
  }

  footer.innerHTML = `
    <section class="border-t border-neutral-900/10 bg-blue-950 text-white">
      <div class="site-container grid gap-10 py-14 md:grid-cols-[1.2fr_0.8fr_0.8fr]">
        <div>
          <div class="flex items-center gap-3">
            <span class="logo-shell logo-shell-light"><img src="${site.logo}" alt="${site.name}" class="h-10 w-auto" /></span>
            <div>
              <p class="font-semibold">${site.name}</p>
              <p class="text-sm text-blue-100/80">${site.tagline}</p>
            </div>
          </div>
          <p class="mt-5 max-w-md text-sm leading-7 text-blue-100/80">Website publik untuk profil komunitas, kegiatan, prestasi, berita, anggota, dan kontak resmi GenBI Jambi.</p>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-white">Navigasi</h3>
          <div class="mt-4 grid gap-2 text-sm text-blue-100/80">
            ${navItems.map((item) => `<a data-transition href="${navHref(item)}" class="w-fit hover:text-white">${item.label}</a>`).join('')}
          </div>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-white">Kontak</h3>
          <div class="mt-4 grid gap-2 text-sm leading-6 text-blue-100/80">
            <a href="mailto:${site.footerEmail || site.email}" class="hover:text-white">${site.footerEmail || site.email}</a>
            <a href="tel:${site.footerPhone || site.phone}" class="hover:text-white">${site.footerPhone || site.phone}</a>
            <p>${site.address}</p>
          </div>
        </div>
      </div>
      <div class="border-t border-white/10 py-5 text-center text-xs text-blue-100/70">${site.footerCopyright || 'Copyright &copy; 2026, GenBI Provinsi Jambi'}</div>
    </section>
    <button id="back-to-top" class="back-to-top" aria-label="Back to top">${icon('arrowUp')}</button>
  `;
}

function setupMobileMenu() {
  const panel = document.querySelector('#mobile-panel');
  const open = document.querySelector('#open-menu');
  const close = document.querySelector('#close-menu');
  if (!panel || !open || !close) return;
  if (panel.dataset.mobileMenuReady === '1') return;
  panel.dataset.mobileMenuReady = '1';

  const show = () => {
    panel.classList.remove('hidden');
    document.body.classList.add('modal-lock');
    open.setAttribute('aria-expanded', 'true');
    window.addEventListener('keydown', onKeydown);
  };

  const hide = () => {
    panel.classList.add('hidden');
    document.body.classList.remove('modal-lock');
    open.setAttribute('aria-expanded', 'false');
    window.removeEventListener('keydown', onKeydown);
  };

  const onKeydown = (event) => {
    if (event.key === 'Escape') hide();
  };

  open.setAttribute('aria-expanded', 'false');
  open.setAttribute('aria-controls', 'mobile-panel');
  open.addEventListener('click', show);
  close.addEventListener('click', hide);
  panel.addEventListener('click', (event) => {
    if (event.target === panel) hide();
  });
  panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', hide));
}

function setupAutoHideHeader() {
  const shell = document.querySelector('#site-header-shell');
  const spacer = document.querySelector('#site-header-spacer');
  if (!shell || !spacer) return;
  if (shell.dataset.autoHideReady === '1') return;
  shell.dataset.autoHideReady = '1';

  let lastScrollY = window.scrollY;
  let ticking = false;
  const revealThreshold = 24;
  const hideThreshold = 96;

  const syncSpacerHeight = () => {
    spacer.style.height = `${shell.offsetHeight}px`;
  };

  const update = () => {
    const currentScrollY = Math.max(window.scrollY, 0);
    const delta = currentScrollY - lastScrollY;
    const nearTop = currentScrollY <= revealThreshold;
    const scrollingUp = delta < 0;
    const scrollingDown = delta > 0;

    shell.classList.toggle('is-scrolled', currentScrollY > 0);

    if (nearTop || scrollingUp) {
      shell.classList.remove('is-hidden');
    } else if (currentScrollY > hideThreshold && scrollingDown) {
      shell.classList.add('is-hidden');
    }

    lastScrollY = currentScrollY;
    ticking = false;
  };

  syncSpacerHeight();
  update();
  window.addEventListener('resize', syncSpacerHeight);
  window.addEventListener('load', syncSpacerHeight);
  window.addEventListener('scroll', () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }, { passive: true });
}

function setupPageTransitions() {
  if (document.body.dataset.pageTransitionsReady === '1') return;
  document.body.dataset.pageTransitionsReady = '1';
  document.body.classList.add('page-ready');

  // Restore visibility when page is loaded from bfcache (back/forward navigation)
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      document.body.classList.remove('page-leaving');
      document.body.classList.add('page-ready');
    }
  });

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[data-transition]');
    if (!link) return;
    const url = new URL(link.href, window.location.href);
    const current = new URL(window.location.href);
    if (url.origin !== current.origin || link.target || event.metaKey || event.ctrlKey) return;
    event.preventDefault();
    document.body.classList.add('page-leaving');
    window.setTimeout(() => {
      window.location.href = url.href;
    }, 130);
  });
}

function setupBackToTop() {
  const button = document.querySelector('#back-to-top');
  if (!button) return;
  if (button.dataset.backToTopReady === '1') return;
  button.dataset.backToTopReady = '1';
  const toggle = () => button.classList.toggle('is-visible', window.scrollY > 500);
  toggle();
  window.addEventListener('scroll', toggle, { passive: true });
  button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

function formatInitials(name) {
  return name
    .split(' ')
    .slice(0, 2)
    .map((part) => part[0])
    .join('')
    .toUpperCase();
}

function getParam(name) {
  const search = new URLSearchParams(window.location.search);
  const value = search.get(name);
  if (value) return value;
  if (name === 'slug' && window.location.pathname.startsWith('/news/')) return decodeURIComponent(window.location.pathname.split('/').filter(Boolean).pop() || '');
  return null;
}

function pageUrl(page) {
  return Core?.pageUrl ? Core.pageUrl(page, window.location) : `/${page}`;
}

function adminUrl(page) {
  return Core?.adminUrl ? Core.adminUrl(page, window.location) : `/admin/${page}`;
}

function routeUrl(name, params = {}) {
  return Core?.routeUrl ? Core.routeUrl(name, params, window.location) : '';
}

function newsDetailUrl(news) {
  return Core?.newsDetailUrl ? Core.newsDetailUrl(news, window.location) : `/news/${news.slug || news.id}`;
}

window.GenBIApp = {
  adminUrl,
  renderShell,
  setupShellInteractions,
  formatInitials,
  getParam,
  icon,
  newsDetailUrl,
  pageUrl,
  routeUrl,
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', setupShellInteractions);
} else {
  setupShellInteractions();
}

})();
