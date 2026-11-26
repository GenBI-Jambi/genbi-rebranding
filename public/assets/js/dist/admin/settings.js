(function(){"use strict";const u=window.GenBIData||{},S=(window.GenBISettingsBootstrap||{}).site||window.GenBISiteSettings||u.site||{},_=u.settingTabs||[],{renderAdminShell:k,icon:$,showToast:g,escapeHtml:r}=window.GenBIAdmin,m=window.GenBIAPI;function y(){const i=(window.location.hash||"").replace("#","").toLowerCase(),a=_.map(t=>t.key);return a.includes(i)?i:a[0]||"logo"}const e={active:y(),site:S};k("settings"),f(),h(),window.addEventListener("hashchange",()=>{const i=y();i!==e.active&&(e.active=i,f(),h())});function f(){const i=document.querySelector("#settings-tabs");i&&(i.innerHTML=_.map(a=>`
      <button type="button" class="admin-tab ${a.key===e.active?"is-active":""}" data-tab="${a.key}">
        ${$(a.icon)}
        ${a.label}
      </button>
    `).join(""),i.querySelectorAll("button").forEach(a=>{a.addEventListener("click",()=>{e.active=a.dataset.tab,history.replaceState(null,"","#"+e.active),f(),h()})}))}function x(i,a,t){return`
      <div class="admin-editor-shell slide-in">
        <header class="admin-editor-head">
          <div>
            <p class="eyebrow">${i}</p>
            <h2 class="serif mt-3 text-3xl sm:text-4xl font-semibold tracking-tight text-[rgb(var(--text-primary))]">${i}</h2>
            <p class="mt-3 max-w-3xl text-sm sm:text-base leading-relaxed sm:leading-7 text-[rgb(var(--text-secondary))]">${a}</p>
          </div>
        </header>
        <div class="admin-editor-canvas">${t}</div>
      </div>
    `}function s(i,a,t,n="text"){const o=n==="textarea"?`<textarea class="input-soft settings-input min-h-32" data-field="${a}">${r(t||"")}</textarea>`:`<input class="input-soft settings-input" data-field="${a}" type="${n}" value="${r(t||"")}">`;return`<label class="settings-field"><span class="settings-field-label">${i}</span>${o}</label>`}function b(i,a,t){return`
      <div class="settings-upload-card">
        <div class="settings-upload-head">
          ${t?`<img src="${r(t)}" alt="${r(i)}" class="settings-upload-preview">`:""}
          <div>
            <p class="settings-upload-title">${i}</p>
            <p class="settings-upload-note">Unggah gambar baru atau masukkan URL langsung, lalu simpan perubahan.</p>
          </div>
        </div>
        <div class="grid gap-3 mt-1">
          <input class="input-soft settings-input" data-field="${a}" type="text" value="${r(t||"")}" placeholder="/uploads/branding/...">
          <input class="admin-file-input settings-file-input" type="file" data-upload-for="${a}" accept="image/*,.ico,.svg">
        </div>
      </div>
    `}function w(i,a,t=""){return`
      <article class="settings-banner-preview">
        <div class="settings-banner-preview-media">
          ${a?`<img src="${r(a)}" alt="${r(i)}" loading="lazy">`:'<div class="settings-banner-preview-empty">Belum ada gambar</div>'}
        </div>
        <div class="settings-banner-preview-copy">
          <p class="settings-banner-preview-label">${r(i)}</p>
          <p class="settings-banner-preview-caption">${r(t||"Preview banner saat ini dari pengaturan live.")}</p>
        </div>
      </article>
    `}function E(){const i=e.site.heroSlides?.[0]||{},a=e.site.heroSlides?.[1]||{},t=i.image||a.image||"",n=i.eyebrow||"Energi untuk Negeri",o=i.title||"Bersama GenBI, tumbuh dan berdampak untuk Jambi.",l=i.caption||"Preview hero halaman utama.";return`
      <section class="settings-hero-preview" style="--settings-hero-image: url('${r(t)}')">
        <div class="settings-hero-preview-overlay"></div>
        <div class="settings-hero-preview-copy">
          <span>${r(n)}</span>
          <h3>${r(o)}</h3>
          <p>${r(l)}</p>
          <div class="settings-hero-preview-actions"><i></i><i></i></div>
        </div>
      </section>
    `}function h(){const i=document.querySelector("#settings-panel");if(!i)return;const a={logo:()=>c("Logo","Logo website untuk publik dan admin.","logo",[b("Logo URL","site.logo_url",e.site.logo)]),favicon:()=>c("Favicon","Favicon browser mengikuti setting live.","favicon",[b("Favicon URL","site.favicon_url",e.site.favicon)]),topbar:()=>c("Top Bar","Kontak cepat pada bagian atas website publik.","topbar",[s("Email","site.topbar_email",e.site.email,"email"),s("Phone","site.topbar_phone",e.site.phone)]),footer:()=>c("Footer","Konten footer dan identitas organisasi.","footer",[s("Site Name","site.name",e.site.name),s("Tagline","site.tagline",e.site.tagline),s("Footer Copyright","site.footer_copyright",e.site.footerCopyright),s("Footer Address","site.footer_address",e.site.address,"textarea"),s("Footer Email","site.footer_email",e.site.footerEmail||e.site.email,"email"),s("Footer Phone","site.footer_phone",e.site.footerPhone||""),s("Recent News Count","site.footer_recent_news_count",e.site.footerRecentNewsCount||3,"number")]),email:()=>c("Email","Alamat pengirim dan penerima email website.","email",[s("Email From","site.email_from",e.site.email,"email"),s("Email To","site.email_to",e.site.footerEmail||e.site.email,"email")]),banner:()=>c("Banner","Copy dan background hero halaman utama.","banner",[E(),`<div class="settings-banner-preview-grid">${w("Banner Image 1",e.site.heroSlides?.[0]?.image||"",e.site.heroSlides?.[0]?.caption||"")}${w("Banner Image 2",e.site.heroSlides?.[1]?.image||"",e.site.heroSlides?.[1]?.caption||"")}</div>`,s("Hero Badge","site.banner_badge",e.site.heroSlides?.[0]?.eyebrow||""),s("Hero Title","site.banner_headline",e.site.heroSlides?.[0]?.title||"","textarea"),s("Hero Subtitle","site.banner_subtitle",e.site.heroSlides?.[0]?.caption||"","textarea"),b("Banner Image 1","site.banner_image_1",e.site.heroSlides?.[0]?.image||""),s("Hero Title Alt","site.banner_headline_alt",e.site.heroSlides?.[1]?.title||"","textarea"),s("Hero Subtitle Alt","site.banner_subtitle_alt",e.site.heroSlides?.[1]?.caption||"","textarea"),b("Banner Image 2","site.banner_image_2",e.site.heroSlides?.[1]?.image||"")]),sidebar:()=>c("Sidebar","Heading sidebar untuk halaman publik.","sidebar",[s("News Heading","site.sidebar_heading_news",e.site.sidebar?.news||""),s("Recent Heading","site.sidebar_heading_recent",e.site.sidebar?.recent||""),s("Upcoming Heading","site.sidebar_heading_upcoming",e.site.sidebar?.upcoming||""),s("Past Heading","site.sidebar_heading_past",e.site.sidebar?.past||""),s("Contact Heading","site.sidebar_heading_contact",e.site.sidebar?.contact||"")]),color:()=>c("Color","Override warna brand utama yang disimpan di settings.","color",[s("Primary","site.color_primary",e.site.colors?.primary||"#114b9a"),s("Primary Hover","site.color_primary_hover",e.site.colors?.primaryHover||"#0c3572"),s("Primary Soft","site.color_primary_soft",e.site.colors?.primarySoft||"#eef6ff")])};i.innerHTML=(a[e.active]||a.logo)(),P(i)}function c(i,a,t,n){return x(i,a,`
      <form class="settings-form" data-endpoint="${t}">
        <div class="settings-form-grid ${t==="banner"?"is-banner":""}">
          ${n.join("")}
        </div>
        <div class="pt-3"><button type="submit" class="btn btn-primary">Simpan ${i}</button></div>
      </form>
    `)}function P(i){i.querySelectorAll("form[data-endpoint]").forEach(a=>{a.addEventListener("submit",async t=>{t.preventDefault();const n=a.dataset.endpoint,o={};a.querySelectorAll("[data-field]").forEach(p=>{o[p.dataset.field]=p.value});const l=await H(`/admin/settings/${n}`,o);l&&l.ok&&(T(o),g("Settings berhasil disimpan."))})}),i.querySelectorAll("[data-upload-for]").forEach(a=>{a.addEventListener("change",async()=>{const t=a.files&&a.files[0];if(!t)return;const n=new FormData;n.append("image",t);const o=await fetch("/admin/settings/upload",{method:"POST",headers:v(!1),body:n}),l=await o.json();if(!o.ok||!l.data?.url){g(l.error||"Upload gagal.");return}const p=i.querySelector(`[data-field="${a.dataset.uploadFor}"]`);p&&(p.value=l.data.url)})})}async function H(i,a){const t=await fetch(i,{method:"POST",headers:{...v(!0),"Content-Type":"application/json"},body:JSON.stringify(a)}),n=await t.json();return t.ok?n:(g(n.error||"Gagal menyimpan settings."),null)}function v(i){const a=m&&m.getCsrfToken&&m.getCsrfToken()||document.querySelector('meta[name="csrf-token"]')?.content||"";return i?{"X-CSRF-TOKEN":a,Accept:"application/json"}:{"X-CSRF-TOKEN":a,Accept:"application/json"}}function T(i){Object.entries(i).forEach(([a,t])=>{switch(a){case"site.logo_url":e.site.logo=t;break;case"site.favicon_url":e.site.favicon=t;break;case"site.topbar_email":e.site.email=t;break;case"site.topbar_phone":e.site.phone=t;break;case"site.name":e.site.name=t;break;case"site.tagline":e.site.tagline=t;break;case"site.footer_address":e.site.address=t;break;case"site.footer_email":e.site.footerEmail=t;break;case"site.footer_phone":e.site.footerPhone=t;break;case"site.footer_copyright":e.site.footerCopyright=t;break;case"site.footer_recent_news_count":e.site.footerRecentNewsCount=t;break;case"site.banner_badge":d(0).eyebrow=t,d(1).eyebrow=t;break;case"site.banner_headline":d(0).title=t;break;case"site.banner_headline_alt":d(1).title=t;break;case"site.banner_subtitle":d(0).caption=t;break;case"site.banner_subtitle_alt":d(1).caption=t;break;case"site.banner_image_1":d(0).image=t;break;case"site.banner_image_2":d(1).image=t;break;case"site.sidebar_heading_news":e.site.sidebar.news=t;break;case"site.sidebar_heading_recent":e.site.sidebar.recent=t;break;case"site.sidebar_heading_upcoming":e.site.sidebar.upcoming=t;break;case"site.sidebar_heading_past":e.site.sidebar.past=t;break;case"site.sidebar_heading_contact":e.site.sidebar.contact=t;break;case"site.color_primary":e.site.colors.primary=t;break;case"site.color_primary_hover":e.site.colors.primaryHover=t;break;case"site.color_primary_soft":e.site.colors.primarySoft=t;break}}),window.GenBISiteSettings={...window.GenBISiteSettings||{},...e.site}}function d(i){return e.site.heroSlides=Array.isArray(e.site.heroSlides)?e.site.heroSlides:[],e.site.heroSlides[i]=e.site.heroSlides[i]||{image:"",eyebrow:"",title:"",caption:""},e.site.sidebar=e.site.sidebar||{},e.site.colors=e.site.colors||{},e.site.heroSlides[i]}})();
