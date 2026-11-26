(function(){
'use strict';
const { renderShell } = window.GenBIApp;
const { observeFadeUp } = window.GenBIUI;
const { aboutBlocks } = window.GenBIData;

renderShell('about');
if (document.body.dataset.ssr !== 'true') {
  renderAbout();
  renderRoles();
}
setupFaqAccordion();
observeFadeUp();

function setupFaqAccordion() {
  const container = document.querySelector('#faq-accordion');
  if (!container) return;
  const items = container.querySelectorAll('.faq-item');
  items.forEach((item) => {
    const summary = item.querySelector('summary');
    if (!summary) return;
    summary.addEventListener('click', () => {
      const willOpen = !item.hasAttribute('open');
      if (willOpen) {
        items.forEach((other) => {
          if (other !== item && other.hasAttribute('open')) {
            other.removeAttribute('open');
          }
        });
      }
    });
  });
}

function renderAbout() {
  const root = document.querySelector('#about-content');
  if (!root) return;
  root.innerHTML = aboutBlocks.map((block) => `
    <section class="border-t border-neutral-900/10 py-9 first:border-t-0 first:pt-0">
      <h2 class="serif text-3xl font-semibold tracking-tight text-neutral-950">${block.title}</h2>
      <p>${block.text}</p>
    </section>
  `).join('');
}

function renderRoles() {
  const root = document.querySelector('#role-list');
  if (!root) return;
  const roles = [
    { title: 'Frontliners Bank Indonesia', text: 'Mengkomunikasikan kelembagaan dan berbagai kebijakan Bank Indonesia kepada mahasiswa dan masyarakat umum.' },
    { title: 'Change Agents', text: 'Menjadi agen perubahan dan role model di kalangan pelajar, mahasiswa, dan masyarakat.' },
    { title: 'Future Leaders', text: 'Mempersiapkan anggota sebagai pemimpin masa depan di berbagai bidang dan tingkatan.' }
  ];
  root.innerHTML = roles.map((role, index) => `
    <article class="soft-row grid gap-4 p-6 md:grid-cols-[80px_1fr]">
      <span class="serif text-4xl font-semibold text-blue-800">0${index + 1}</span>
      <div>
        <h3 class="text-lg font-bold text-neutral-950">${role.title}</h3>
        <p class="mt-2 text-sm leading-7 text-neutral-600">${role.text}</p>
      </div>
    </article>
  `).join('');
}

})();
