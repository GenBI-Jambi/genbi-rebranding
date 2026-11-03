(function(){"use strict";const{formatInitials:d,renderShell:x}=window.GenBIApp,{createCustomSelect:m,createModalController:q,observeFadeUp:L,safeImage:g,unique:y}=window.GenBIUI,k=window.GenBIAPI;x("team"),L();const v=document.querySelector('#team-list[data-ssr="true"]');if(v){document.body.classList.add("page-ready");const t=document.querySelector("#team-layout-grid"),e=document.querySelector("#team-layout-list");t?.addEventListener("click",()=>{v.className="fade-up team-public-grid in-view",t.classList.add("is-active"),e?.classList.remove("is-active")}),e?.addEventListener("click",()=>{v.className="fade-up team-public-list in-view",e.classList.add("is-active"),t?.classList.remove("is-active")});const i=document.querySelector("#team-filter-form"),c=document.querySelector("#team-search");c&&i&&c.addEventListener("keydown",o=>{o.key==="Enter"&&(o.preventDefault(),i.submit())});return}let n=[],s={divisions:[],campuses:[],years:[]},a={query:"",division:"Semua",campus:"Semua",year:"Semua",page:1,perPage:12,layout:"grid"};E();const h=document.querySelector("#team-search"),f=document.querySelector("#team-count"),u=document.querySelector("#team-list"),p=document.querySelector("#team-pagination"),$=document.querySelector("#team-layout-grid"),b=document.querySelector("#team-layout-list"),M=q(document.querySelector("#member-modal"));B();async function B(){f.textContent="Memuat data anggota...",h.value=a.query,w();const t=await k.getTeamList();n=t.members,s=t.filters||s,m(document.querySelector("#team-division"),{label:"Divisi",options:y(s.divisions.length?s.divisions:n.map(e=>e.division)),value:a.division,onChange:e=>{a.division=e,a.page=1,r(),l()}}),m(document.querySelector("#team-campus"),{label:"Komisariat/Kampus",options:y(s.campuses.length?s.campuses:n.map(e=>e.campus)),value:a.campus,onChange:e=>{a.campus=e,a.page=1,r(),l()}}),m(document.querySelector("#team-year"),{label:"Tahun",options:y(s.years.length?s.years:n.map(e=>e.year)),value:a.year,onChange:e=>{a.year=e,a.page=1,r(),l()}}),h.addEventListener("input",e=>{a.query=e.target.value,a.page=1,r(),l()}),$?.addEventListener("click",()=>S("grid")),b?.addEventListener("click",()=>S("list")),document.querySelector("#team-reset").addEventListener("click",()=>{window.location.href=window.location.pathname}),l()}function S(t){a.layout=t,r(),w(),l()}function w(){$?.classList.toggle("is-active",a.layout==="grid"),b?.classList.toggle("is-active",a.layout==="list")}function E(){const t=new URLSearchParams(window.location.search),e=t.get("view")||t.get("layout");(e==="grid"||e==="list")&&(a.layout=e),a.query=t.get("q")||"",a.division=t.get("division")||"Semua",a.campus=t.get("campus")||"Semua",a.year=t.get("year")||"Semua",a.page=Math.max(1,Number(t.get("page"))||1)}function r(){const t=new URLSearchParams;a.layout!=="grid"&&t.set("view",a.layout),a.query.trim()&&t.set("q",a.query.trim()),a.division!=="Semua"&&t.set("division",a.division),a.campus!=="Semua"&&t.set("campus",a.campus),a.year!=="Semua"&&t.set("year",a.year),a.page>1&&t.set("page",String(a.page));const e=t.toString(),i=e?`${window.location.pathname}?${e}`:window.location.pathname;window.history.replaceState({},"",i)}function I(){const t=a.query.toLowerCase().trim();return n.filter(e=>{const i=`${e.name} ${e.role} ${e.division} ${e.campus} ${e.commission} ${e.year}`.toLowerCase();return(!t||i.includes(t))&&(a.division==="Semua"||e.division===a.division)&&(a.campus==="Semua"||e.campus===a.campus||e.commission===a.campus)&&(a.year==="Semua"||String(e.year)===String(a.year))})}function l(){const t=I(),e=Math.max(1,Math.ceil(t.length/a.perPage));a.page=Math.min(a.page,e),r();const i=(a.page-1)*a.perPage,c=t.slice(i,i+a.perPage);f.textContent=t.length?`Menampilkan ${i+1}-${Math.min(i+a.perPage,t.length)} dari ${t.length} anggota (${n.length} total).`:`Belum ada data yang cocok dari ${n.length} anggota.`,u.className=`fade-up ${a.layout==="list"?"team-public-list":"team-public-grid"} in-view`,u.innerHTML=c.length?c.map(o=>a.layout==="list"?T(o):C(o)).join(""):'<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600">Belum ada data yang cocok dengan filter.</div>',u.querySelectorAll(".open-member").forEach(o=>{o.addEventListener("click",()=>N(Number(o.dataset.id)))}),D(e)}function C(t){return`
    <article class="team-public-card">
      <button class="team-public-photo open-member" data-id="${t.id}" aria-label="Detail ${t.name}">
        ${t.photo?`<img src="${g(t.photo)}" alt="${t.name}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${d(t.name)}'))" />`:`<span>${d(t.name)}</span>`}
      </button>
      <div>
        <p class="eyebrow">${t.year||"GenBI Jambi"}</p>
        <h3 class="serif mt-2 text-2xl font-semibold tracking-tight text-neutral-950">${t.name}</h3>
        <p class="mt-2 text-sm font-semibold text-blue-800">${t.role}</p>
        <p class="mt-3 text-sm leading-6 text-neutral-600">${t.division}</p>
        <p class="text-sm leading-6 text-neutral-500">${t.campus}</p>
      </div>
      <button class="btn btn-secondary open-member" data-id="${t.id}">Detail</button>
    </article>
  `}function T(t){return`
    <article class="team-public-row">
      <button class="team-public-row-photo open-member" data-id="${t.id}" aria-label="Detail ${t.name}">
        ${t.photo?`<img src="${g(t.photo)}" alt="${t.name}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${d(t.name)}'))" />`:`<span>${d(t.name)}</span>`}
      </button>
      <div>
        <h3 class="text-base font-bold text-neutral-950">${t.name}</h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600">${t.role}</p>
      </div>
      <div class="text-sm leading-6 text-neutral-600">
        <p class="font-semibold text-neutral-800">${t.division}</p>
        <p>${t.campus}</p>
      </div>
      <div class="text-sm font-semibold text-neutral-500">${t.year}</div>
      <button class="btn btn-secondary open-member" data-id="${t.id}">Detail</button>
    </article>
  `}function P(t,e){return e<=7?Array.from({length:e},(i,c)=>c+1):t<=4?[1,2,3,4,5,"...",e]:t>=e-3?[1,"...",e-4,e-3,e-2,e-1,e]:[1,"...",t-1,t,t+1,"...",e]}function D(t){if(!p)return;if(t<=1){p.innerHTML="";return}const e=P(a.page,t);p.innerHTML=`
    <button class="pager-button" type="button" data-page="${Math.max(1,a.page-1)}" ${a.page===1?'disabled aria-disabled="true"':""}>Sebelumnya</button>
    ${e.map(i=>i==="..."?'<span class="pager-ellipsis" aria-hidden="true">&hellip;</span>':`<button class="pager-button ${i===a.page?"is-active":""}" type="button" data-page="${i}" ${i===a.page?'aria-current="page"':""}>${i}</button>`).join("")}
    <button class="pager-button" type="button" data-page="${Math.min(t,a.page+1)}" ${a.page===t?'disabled aria-disabled="true"':""}>Berikutnya</button>
  `,p.querySelectorAll("[data-page]").forEach(i=>{i.addEventListener("click",()=>{i.disabled||i.getAttribute("aria-disabled")==="true"||(a.page=Number(i.dataset.page)||1,r(),l(),u.scrollIntoView({behavior:"smooth",block:"start"}))})})}function N(t){const e=n.find(i=>i.id===t);e&&M.open({content:`
    <div class="public-modal-panel team-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="member-title">
      <button class="btn-icon modal-close" aria-label="Tutup detail anggota">\xD7</button>
      <div class="team-detail-photo">
        ${e.photo?`<img src="${g(e.photo)}" alt="${e.name}" />`:`<span>${d(e.name)}</span>`}
      </div>
      <div class="team-detail-content">
        <div class="team-detail-heading">
          <p class="eyebrow">Detail anggota</p>
          <h3 id="member-title" class="serif mt-1 text-3xl font-semibold tracking-tight text-neutral-950">${e.name}</h3>
        </div>
        <div class="team-detail-info-grid">
          <div class="team-detail-info-card"><span>Jabatan</span><strong>${e.role}</strong></div>
          <div class="team-detail-info-card"><span>Divisi</span><strong>${e.division}</strong></div>
          <div class="team-detail-info-card"><span>Komisariat/Kampus</span><strong>${e.campus}</strong></div>
          <div class="team-detail-info-card"><span>Tahun</span><strong>${e.year}</strong></div>
        </div>
        ${e.bio?`<div class="team-detail-bio">${e.bio}</div>`:""}
      </div>
    </div>
  `})}})();
