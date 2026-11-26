(function(){"use strict";const{renderShell:P}=window.GenBIApp||{},{createModalController:C,observeFadeUp:k}=window.GenBIUI||{},M=window.GenBIAPI||{},{news:w}=window.GenBIData||{};typeof P=="function"&&P("prestasi"),N();const u=document.querySelector("#prestasi-modal");let $=null;function L(){$=null,m&&m.close(),u&&(u.classList.add("hidden"),u.innerHTML=""),document.body.classList.remove("modal-lock"),document.documentElement.classList.remove("modal-lock");const a=document.querySelector("#back-to-top");a&&a.style.removeProperty("display")}const m=typeof C=="function"&&u?C(u,{onClose:()=>{document.body.classList.remove("modal-lock"),document.documentElement.classList.remove("modal-lock");const a=document.querySelector("#back-to-top");a&&a.style.removeProperty("display"),$=null}}):null;let b=Array.isArray(window.prestasiData)?[...window.prestasiData]:[],A="grid";function i(a=""){return String(a??"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}function N(){let a=document.querySelector("#prestasi-modal");a||(a=document.createElement("div"),a.id="prestasi-modal",a.className="public-fixed-modal hidden",document.body.appendChild(a)),a.addEventListener("click",t=>{(t.target===a||t.target.closest(".prestasi-detail-close, [data-modal-close], .modal-close"))&&(t.preventDefault(),L())})}window.addEventListener("keydown",a=>{if(a.key==="Escape"){const t=document.querySelector("#prestasi-modal");t&&!t.classList.contains("hidden")&&L()}});function z(a={}){const t=String(a.title||"Prestasi Anggota GenBI").trim(),s=String(a.name||a.member_name||"-").trim();let r=String(a.campus||"").trim(),e="",g=String(a.category||a.kategori||"Prestasi").trim();const f=String(a.year||a.tahun||"").trim();let d=String(a.institution||a.penyelenggara||"").trim();const l=String(a.description||a.deskripsi||"").trim();let o="",c="",j="",I=[],R="";if(l.includes("|")?l.split("|").map(p=>p.trim()).filter(Boolean).forEach(p=>{const x=p.indexOf(":");if(x>-1){const G=p.substring(0,x).trim(),n=G.toLowerCase(),y=p.substring(x+1).trim();n==="program studi"||n==="prodi"||n==="jurusan"?e=y:n==="kampus"||n==="asal kampus"||n==="universitas"||n==="komisariat"?(!r||r==="-")&&(r=y):n==="acara"||n==="nama acara"||n==="kegiatan"||n==="nama kegiatan"?o=y:n==="penyelenggara"||n==="diadakan oleh"||n==="institusi"?c=y:n==="prestasi"||n==="capaian"||n==="juara"?j=y:I.push({label:G,val:y})}else I.push({label:"",val:p})}):R=l,!o&&d&&(o=d),o){const v=o.match(/\s+(?:yang\s+)?diadakan\s+oleh\s+/i);if(v&&v.index!==void 0){const p=o.substring(0,v.index).trim(),x=o.substring(v.index+v[0].length).trim();o=p,c||(c=x)}}return!c&&d&&d!==o&&(c=d),{title:t,name:s,campus:r,prodi:e,category:g,year:f,namaAcara:o,penyelenggara:c,capaian:j,extraRows:I,narrativeText:R}}function h(a=0){return Array.isArray(w)&&w.length>0&&w[Math.abs(a)%w.length]?.image||"https://genbijambi.com/public/uploads/slider-1.png"}function S(a={},t=0){const s=a.image||a.photo||Array.isArray(a.images)&&a.images[0]||"";return s&&typeof s=="string"&&s.trim()!==""?s:h(t)}function H(){return`
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
    `}function K(a={},t=0){const s=h(t),r=S(a,t),e=z(a),g=a.slug||a.id||"",f=`/prestasi/${encodeURIComponent(g)}`,d=e.category&&e.category.toLowerCase()!==e.title.toLowerCase()&&e.category.length<=28?e.category:"GenBI Prestasi";return`
      <div class="public-modal-panel prestasi-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="prestasi-modal-title">
        <button type="button" class="prestasi-detail-close modal-close" data-modal-close aria-label="Tutup ringkasan prestasi" title="Tutup">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <figure class="prestasi-detail-image">
          <img src="${r}" alt="Dokumentasi ${i(e.title)}" onerror="this.src='${s}'" />
        </figure>

        <div class="prestasi-detail-copy">
          <div class="flex flex-wrap items-center gap-2 pr-10">
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#114b9a] border border-blue-200/60">
              ${i(d)}
            </span>
            ${e.year?`<span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-600">Tahun ${i(e.year)}</span>`:""}
          </div>

          <h2 id="prestasi-modal-title" class="serif mt-3 text-2xl font-bold leading-tight tracking-tight text-neutral-950 md:text-3xl">
            ${i(e.title)}
          </h2>

          <!-- 1. DATA PENERIMA PRESTASI (DATA PRIBADI) -->
          <div class="prestasi-card-block">
            <div class="prestasi-card-tag">Data Penerima Prestasi</div>
            <div class="prestasi-person-row">
              <div class="prestasi-person-avatar">
                <svg class="h-4 w-4 text-[#114b9a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
              </div>
              <div class="min-w-0 flex-1">
                <h3 class="prestasi-person-name">${i(e.name)}</h3>
              </div>
            </div>
            <div class="prestasi-data-grid">
              ${e.prodi?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Program Studi</span>
                  <span class="prestasi-data-val">${i(e.prodi)}</span>
                </div>
              `:""}
              ${e.campus?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Asal Kampus / Komisariat</span>
                  <span class="prestasi-data-val">${i(e.campus)}</span>
                </div>
              `:""}
            </div>
          </div>

          <!-- 2. INFORMASI KEGIATAN & PERLOMBAAN -->
          <div class="prestasi-card-block">
            <div class="prestasi-card-tag">Informasi Kegiatan & Perlombaan</div>
            <div class="prestasi-data-grid">
              ${e.namaAcara?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Nama Kegiatan / Acara</span>
                  <span class="prestasi-data-val font-semibold text-neutral-900">${i(e.namaAcara)}</span>
                </div>
              `:""}
              ${e.penyelenggara?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Penyelenggara / Diadakan Oleh</span>
                  <span class="prestasi-data-val">${i(e.penyelenggara)}</span>
                </div>
              `:""}
              ${e.capaian&&e.capaian.toLowerCase()!==e.title.toLowerCase()?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Capaian Prestasi</span>
                  <span class="prestasi-data-val">${i(e.capaian)}</span>
                </div>
              `:""}
              ${e.extraRows.map(l=>`
                <div class="prestasi-data-item">
                  ${l.label?`<span class="prestasi-data-label">${i(l.label)}</span>`:""}
                  <span class="prestasi-data-val">${i(l.val)}</span>
                </div>
              `).join("")}
              ${e.narrativeText?`
                <div class="prestasi-data-item">
                  <span class="prestasi-data-val leading-relaxed text-neutral-600">${i(e.narrativeText)}</span>
                </div>
              `:""}
            </div>
          </div>

          <div class="prestasi-detail-footer">
            <a data-transition href="${f}" class="btn btn-primary inline-flex items-center justify-center gap-2 w-full sm:w-auto" aria-label="Baca detail lengkap mengenai ${i(e.title)}">
              <span>Baca Detail Lengkap</span>
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    `}async function E(a,t){a&&(a.preventDefault(),a.stopPropagation());const s=t.dataset.id||"",r=t.dataset.slug||s,e=Number(t.dataset.index||0);let g=(b||[]).find(l=>String(l.id)===String(s)||String(l.slug)===String(r));!g&&t.dataset.title&&(g={id:s,slug:r,title:t.dataset.title||"",name:t.dataset.name||"",campus:t.dataset.campus||"",category:t.dataset.category||"",year:t.dataset.year||"",description:t.dataset.description||"",image:t.dataset.image||"",institution:t.dataset.institution||""}),$=s;const f=document.querySelector("#back-to-top");if(f&&f.style.setProperty("display","none","important"),m&&m.open({content:H(),trigger:t}),await new Promise(l=>setTimeout(l,260)),$!==s)return;const d=g||{title:"Ringkasan Prestasi",id:s,slug:r};if(m){m.open({content:K(d,e),trigger:t}),u&&u.querySelectorAll(".prestasi-detail-close, [data-modal-close]").forEach(o=>{o.addEventListener("click",c=>{c.preventDefault(),c.stopPropagation(),L()})});const l=document.querySelector("#prestasi-modal .prestasi-detail-image img");l&&l.addEventListener("error",()=>{l.src=h(e)})}}function D(a){a&&a.querySelectorAll("[data-id]").forEach(t=>{t.addEventListener("click",s=>E(s,t)),t.addEventListener("keydown",s=>{(s.key==="Enter"||s.key===" ")&&(s.preventDefault(),E(s,t))})})}function B(){document.querySelectorAll("[data-prestasi-layout]").forEach(a=>{a.addEventListener("click",t=>{t.preventDefault();const s=a.dataset.prestasiLayout||"grid";A=s,document.querySelectorAll("[data-prestasi-layout]").forEach(e=>{e.classList.toggle("is-active",e.dataset.prestasiLayout===s)});const r=document.querySelector("#prestasi-list");r&&(r.className=s==="grid"?"prestasi-grid":"soft-card overflow-hidden prestasi-list")})})}const T=document.querySelector('#prestasi-list[data-ssr="true"]');if(T){document.body.classList.add("page-ready"),typeof k=="function"&&k(),B(),D(T);return}const q=document.querySelector("#prestasi-list");q&&(B(),O(q));async function O(a){a.innerHTML=`
      <div class="flex flex-col items-center justify-center p-12 text-center">
        <svg class="h-8 w-8 animate-spin text-[#114b9a]" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="mt-3 text-sm font-medium text-neutral-500">Memuat data prestasi...</span>
      </div>
    `;try{typeof M.getPrestasiList=="function"&&(b=await M.getPrestasiList())}catch(t){console.warn("Gagal memuat API prestasi:",t)}U(a)}function U(a){if(a.className=A==="grid"?"prestasi-grid":"soft-card overflow-hidden prestasi-list",!Array.isArray(b)||b.length===0){a.innerHTML='<div class="p-8 text-center text-sm text-neutral-600">Belum ada data prestasi yang tersedia.</div>';return}a.innerHTML=b.map((t,s)=>A==="grid"?_(t,s):F(t,s)).join(""),D(a),typeof k=="function"&&k()}function F(a,t){const s=S(a,t),r=h(t);return`
      <button type="button" class="prestasi-row soft-row text-left cursor-pointer" data-id="${a.id}" data-slug="${i(a.slug||a.id)}" data-index="${t}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-row-image">
          <img src="${s}" alt="Dokumentasi ${i(a.title)}" loading="lazy" onerror="this.src='${r}'" />
        </figure>
        <span class="serif prestasi-number">${String(t+1).padStart(2,"0")}</span>
        <div class="prestasi-row-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${i(a.category||"Prestasi")}</span><span>\u2022</span><span>${i(String(a.year||""))}</span>
          </div>
          <h3 class="serif mt-2 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${i(a.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${i(a.description||"")}</p>
        </div>
        <div class="prestasi-person">
          <strong>${i(a.name||a.member_name||"")}</strong><br />${i(a.campus||a.institution||"")}
        </div>
      </button>
    `}function _(a,t){const s=S(a,t),r=h(t);return`
      <button type="button" class="prestasi-grid-card soft-card text-left cursor-pointer" data-id="${a.id}" data-slug="${i(a.slug||a.id)}" data-index="${t}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-grid-image">
          <img src="${s}" alt="Dokumentasi ${i(a.title)}" loading="lazy" onerror="this.src='${r}'" />
        </figure>
        <div class="prestasi-grid-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${i(a.category||"Prestasi")}</span><span>\u2022</span><span>${i(String(a.year||""))}</span>
          </div>
          <h3 class="serif mt-3 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${i(a.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${i(a.description||"")}</p>
          <p class="mt-4 rounded-2xl bg-blue-50 px-4 py-3 text-sm leading-6 text-blue-950">
            <strong>${i(a.name||a.member_name||"")}</strong><br />${i(a.campus||a.institution||"")}
          </p>
        </div>
      </button>
    `}})();
