(function(){"use strict";const{renderShell:x}=window.GenBIApp||{},{createModalController:y,observeFadeUp:c}=window.GenBIUI||{},h=window.GenBIAPI||{},{news:u}=window.GenBIData||{};typeof x=="function"&&x("prestasi"),A();const v=document.querySelector("#prestasi-modal"),p=typeof y=="function"&&v?y(v):null;let l=Array.isArray(window.prestasiData)?[...window.prestasiData]:[],f="grid",k=null;function s(t=""){return String(t??"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}function A(){if(document.querySelector("#prestasi-modal"))return;const t=document.createElement("div");t.id="prestasi-modal",t.className="public-fixed-modal hidden",document.body.appendChild(t)}function o(t=0){return Array.isArray(u)&&u.length>0&&u[Math.abs(t)%u.length]?.image||"https://genbijambi.com/public/uploads/slider-1.png"}function m(t={},e=0){const a=t.image||t.photo||Array.isArray(t.images)&&t.images[0]||"";return a&&typeof a=="string"&&a.trim()!==""?a:o(e)}function P(){return`
      <div class="public-modal-panel prestasi-loading-panel modal-panel is-open" role="dialog" aria-modal="true" aria-label="Memuat ringkasan prestasi">
        <div class="relative flex items-center justify-center my-3">
          <div class="w-14 h-14 rounded-full border-4 border-blue-100 animate-pulse"></div>
          <svg class="w-14 h-14 absolute animate-spin text-[#114b9a]" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <div class="w-2.5 h-2.5 rounded-full bg-[#114b9a] absolute shadow-sm"></div>
        </div>
        <h4 class="mt-4 text-base font-semibold text-neutral-900 tracking-tight font-sans">Memuat Ringkasan Prestasi</h4>
        <p class="mt-1.5 text-xs text-neutral-500 font-normal">Mohon tunggu sebentar...</p>
      </div>
    `}function B(t={},e=0){const a=o(e),i=m(t,e),r=s(t.title||"Prestasi Anggota GenBI"),d=s(t.name||t.member_name||"-"),g=s(t.campus||t.institution||"-"),n=s(t.category||t.kategori||"Prestasi"),C=s(String(t.year||t.tahun||"")),b=s(t.institution||t.penyelenggara||""),M=s(t.description||t.deskripsi||""),G=t.slug||t.id||"",H=`/prestasi/${encodeURIComponent(G)}`;return`
      <div class="public-modal-panel prestasi-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="prestasi-modal-title">
        <button type="button" class="prestasi-detail-close modal-close" aria-label="Tutup ringkasan prestasi">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <figure class="prestasi-detail-image">
          <img src="${i}" alt="Dokumentasi ${r}" onerror="this.src='${a}'" />
        </figure>

        <div class="prestasi-detail-copy">
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#114b9a] border border-blue-200/50">
              ${n}
            </span>
            ${C?`<span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-600">Tahun ${C}</span>`:""}
          </div>

          <h2 id="prestasi-modal-title" class="serif mt-3 text-2xl font-bold leading-tight tracking-tight text-neutral-950 md:text-3xl">
            ${r}
          </h2>

          <div class="mt-4 rounded-xl bg-blue-50/60 border border-blue-100/80 p-3.5 text-sm leading-relaxed text-blue-950">
            <div class="text-[0.6875rem] font-bold uppercase tracking-wider text-[#114b9a]">Penerima Prestasi</div>
            <strong class="text-base font-semibold text-neutral-900 block mt-0.5">${d}</strong>
            <span class="text-xs text-neutral-600">${g}${b&&b!==g?" \u2022 "+b:""}</span>
          </div>

          ${M?`<p class="mt-3.5 text-sm leading-relaxed text-neutral-600">${M}</p>`:""}

          <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-neutral-100 pt-5">
            <a data-transition href="${H}" class="btn btn-primary flex-1 sm:flex-initial inline-flex items-center justify-center gap-2" aria-label="Baca detail lengkap mengenai ${r}">
              <span>Baca Detail Lengkap</span>
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    `}async function w(t,e){t&&(t.preventDefault(),t.stopPropagation());const a=e.dataset.id||"",i=e.dataset.slug||a,r=Number(e.dataset.index||0);let d=(l||[]).find(n=>String(n.id)===String(a)||String(n.slug)===String(i));if(!d&&e.dataset.title&&(d={id:a,slug:i,title:e.dataset.title||"",name:e.dataset.name||"",campus:e.dataset.campus||"",category:e.dataset.category||"",year:e.dataset.year||"",description:e.dataset.description||"",image:e.dataset.image||"",institution:e.dataset.institution||""}),k=a,p&&p.open({content:P(),trigger:e}),await new Promise(n=>setTimeout(n,260)),k!==a)return;const g=d||{title:"Ringkasan Prestasi",id:a,slug:i};if(p){p.open({content:B(g,r),trigger:e});const n=document.querySelector("#prestasi-modal .prestasi-detail-image img");n&&n.addEventListener("error",()=>{n.src=o(r)})}}function $(t){t&&t.querySelectorAll("[data-id]").forEach(e=>{e.addEventListener("click",a=>w(a,e)),e.addEventListener("keydown",a=>{(a.key==="Enter"||a.key===" ")&&(a.preventDefault(),w(a,e))})})}function L(){document.querySelectorAll("[data-prestasi-layout]").forEach(t=>{t.addEventListener("click",e=>{e.preventDefault();const a=t.dataset.prestasiLayout||"grid";f=a,document.querySelectorAll("[data-prestasi-layout]").forEach(r=>{r.classList.toggle("is-active",r.dataset.prestasiLayout===a)});const i=document.querySelector("#prestasi-list");i&&(i.className=a==="grid"?"prestasi-grid":"soft-card overflow-hidden prestasi-list")})})}const I=document.querySelector('#prestasi-list[data-ssr="true"]');if(I){document.body.classList.add("page-ready"),typeof c=="function"&&c(),L(),$(I);return}const S=document.querySelector("#prestasi-list");S&&(L(),D(S));async function D(t){t.innerHTML=`
      <div class="flex flex-col items-center justify-center p-12 text-center">
        <svg class="h-8 w-8 animate-spin text-[#114b9a]" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="mt-3 text-sm font-medium text-neutral-500">Memuat data prestasi...</span>
      </div>
    `;try{typeof h.getPrestasiList=="function"&&(l=await h.getPrestasiList())}catch(e){console.warn("Gagal memuat API prestasi:",e)}E(t)}function E(t){if(t.className=f==="grid"?"prestasi-grid":"soft-card overflow-hidden prestasi-list",!Array.isArray(l)||l.length===0){t.innerHTML='<div class="p-8 text-center text-sm text-neutral-600">Belum ada data prestasi yang tersedia.</div>';return}t.innerHTML=l.map((e,a)=>f==="grid"?j(e,a):q(e,a)).join(""),$(t),typeof c=="function"&&c()}function q(t,e){const a=m(t,e),i=o(e);return`
      <button type="button" class="prestasi-row soft-row text-left cursor-pointer" data-id="${t.id}" data-slug="${s(t.slug||t.id)}" data-index="${e}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-row-image">
          <img src="${a}" alt="Dokumentasi ${s(t.title)}" loading="lazy" onerror="this.src='${i}'" />
        </figure>
        <span class="serif prestasi-number">${String(e+1).padStart(2,"0")}</span>
        <div class="prestasi-row-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${s(t.category||"Prestasi")}</span><span>\u2022</span><span>${s(String(t.year||""))}</span>
          </div>
          <h3 class="serif mt-2 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${s(t.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${s(t.description||"")}</p>
        </div>
        <div class="prestasi-person">
          <strong>${s(t.name||t.member_name||"")}</strong><br />${s(t.campus||t.institution||"")}
        </div>
      </button>
    `}function j(t,e){const a=m(t,e),i=o(e);return`
      <button type="button" class="prestasi-grid-card soft-card text-left cursor-pointer" data-id="${t.id}" data-slug="${s(t.slug||t.id)}" data-index="${e}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-grid-image">
          <img src="${a}" alt="Dokumentasi ${s(t.title)}" loading="lazy" onerror="this.src='${i}'" />
        </figure>
        <div class="prestasi-grid-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${s(t.category||"Prestasi")}</span><span>\u2022</span><span>${s(String(t.year||""))}</span>
          </div>
          <h3 class="serif mt-3 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${s(t.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${s(t.description||"")}</p>
          <p class="mt-4 rounded-2xl bg-blue-50 px-4 py-3 text-sm leading-6 text-blue-950">
            <strong>${s(t.name||t.member_name||"")}</strong><br />${s(t.campus||t.institution||"")}
          </p>
        </div>
      </button>
    `}})();
