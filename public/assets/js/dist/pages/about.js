(function(){"use strict";const{renderShell:i}=window.GenBIApp,{observeFadeUp:o}=window.GenBIUI,{aboutBlocks:s}=window.GenBIData;i("about"),document.body.dataset.ssr!=="true"&&(c(),d()),l(),o();function l(){const e=document.querySelector("#faq-accordion");if(!e)return;const t=e.querySelectorAll(".faq-item");t.forEach(n=>{const a=n.querySelector("summary");a&&a.addEventListener("click",()=>{!n.hasAttribute("open")&&t.forEach(r=>{r!==n&&r.hasAttribute("open")&&r.removeAttribute("open")})})})}function c(){const e=document.querySelector("#about-content");e&&(e.innerHTML=s.map(t=>`
    <section class="border-t border-neutral-900/10 py-9 first:border-t-0 first:pt-0">
      <h2 class="serif text-3xl font-semibold tracking-tight text-neutral-950">${t.title}</h2>
      <p>${t.text}</p>
    </section>
  `).join(""))}function d(){const e=document.querySelector("#role-list");if(!e)return;const t=[{title:"Frontliners Bank Indonesia",text:"Mengkomunikasikan kelembagaan dan berbagai kebijakan Bank Indonesia kepada mahasiswa dan masyarakat umum."},{title:"Change Agents",text:"Menjadi agen perubahan dan role model di kalangan pelajar, mahasiswa, dan masyarakat."},{title:"Future Leaders",text:"Mempersiapkan anggota sebagai pemimpin masa depan di berbagai bidang dan tingkatan."}];e.innerHTML=t.map((n,a)=>`
    <article class="soft-row grid gap-4 p-6 md:grid-cols-[80px_1fr]">
      <span class="serif text-4xl font-semibold text-blue-800">0${a+1}</span>
      <div>
        <h3 class="text-lg font-bold text-neutral-950">${n.title}</h3>
        <p class="mt-2 text-sm leading-7 text-neutral-600">${n.text}</p>
      </div>
    </article>
  `).join("")}})();
