function getStaticCatalogueHTML(){return`
    <li><a href="../assets/catalogues/A_BUBBLE GUM & CHEWING GUM.pdf" download>Bubble Gum & Chewing Gum</a></li>
    <li><a href="../assets/catalogues/B_MOULDED CHOCOLATE & MILK.pdf" download>Moulded Chocolate & Milk</a></li>
    <li><a href="../assets/catalogues/C_LIQUID CHOCOLATES & MILK.pdf" download>Liquid Chocolates & Milk</a></li>
    <li><a href="../assets/catalogues/D_GEMS & CHOCO BEANS.pdf" download>Gems & Choco Beans</a></li>
    <li><a href="../assets/catalogues/E_GOLD_COIN.pdf" download>Gold Coin</a></li>
    <li><a href="../assets/catalogues/F_CAKE.pdf" download>Cake</a></li>
    <li><a href="../assets/catalogues/FRYUMS.pdf" download>Fryums</a></li>
    <li><a href="../assets/catalogues/G_CC_STICKS.pdf" download>Cc Sticks</a></li>
    <li><a href="../assets/catalogues/H_BISCUITS.pdf" download>Biscuits</a></li>
    <li><a href="../assets/catalogues/I_CHOCOLATE COATED BISCUITS.pdf" download>Chocolate Coated Biscuits</a></li>
    <li><a href="../assets/catalogues/J_TOYS&KHAJANA.pdf" download>Toys & Khajana</a></li>
    <li><a href="../assets/catalogues/K_WAFFER & CREME STICK.pdf" download>Waffer & Creme Stick</a></li>
    <li><a href="../assets/catalogues/KAMCO SWEETS.pdf" download>Kamco Sweets</a></li>
    <li><a href="../assets/catalogues/L_TOFFEE.pdf" download>Toffee</a></li>
    <li><a href="../assets/catalogues/M_FRUIT BOLAL.pdf" download>Fruit Ball</a></li>
    <li><a href="../assets/catalogues/Moulded jelly 2024.pdf" download>Moulded Jelly 2024</a></li>
    <li><a href="../assets/catalogues/N_LIQUID_JELLY_CUPS.pdf" download>Liquid Jelly Cups</a></li>
    <li><a href="../assets/catalogues/P_ALL_DIBBI_ITEMS.pdf" download>All Dibbi Items</a></li>
    <li><a href="../assets/catalogues/Q_TAMARIND PASTE.pdf" download>Tamarind Paste</a></li>
    <li><a href="../assets/catalogues/R_COCONUTS.pdf" download>Coconuts</a></li>
    <li><a href="../assets/catalogues/S_LOLLIPOP.pdf" download>Lollipop</a></li>
    <li><a href="../assets/catalogues/T_4_JOLLY_JELLY.pdf" download>4 Jolly Jelly</a></li>
    <li><a href="../assets/catalogues/U_CANDY(Sugar Boiled Candy).pdf" download>Candy (Sugar Boiled)</a></li>
    <li><a href="../assets/catalogues/V_CHOCO_CONE.pdf" download>Choco Cone</a></li>
    <li><a href="../assets/catalogues/W_Mouth_Freshner.pdf" download>Mouth Freshner</a></li>
    <li><a href="../assets/catalogues/X_SOFT DRINK&JALJEERA.pdf" download>Soft Drink & Jaljeera</a></li>
`}function isMobileView(){return window.innerWidth<=768}async function fetchCategories(){try{let e=isMobileView()?"backend/fetch_categories_mobile.php":"backend/fetch_categories_desktop.php",a=await fetch(e);if(!a.ok)throw Error("Network response was not ok");let o=await a.json(),l=['<li><a href="shop.html">All Product</a></li>',...o.map(e=>`
            <li><a href="shop.html?category=${encodeURIComponent(e.name)}">${e.name}</a></li>
        `)].join("");if(isMobileView()){let t=document.querySelector(".menu-panel-products-dropdown"),s=document.querySelector(".menu-panel-catalogues-dropdown");t&&(t.innerHTML=l),s&&(s.innerHTML=getStaticCatalogueHTML())}else{let n=document.querySelector(".dropdown-menu"),i=document.querySelector(".catalogueDropdown-menu");n&&(n.innerHTML=l),i&&(i.innerHTML=getStaticCatalogueHTML())}}catch(d){console.error("Error fetching categories:",d)}}function showProductsDropDown(e){e||(e=window.event),e.preventDefault();let a=document.querySelector(".menu-panel-products-dropdown");a&&(a.style.display="none"===a.style.display?"block":"none")}let isCatalogueMenuPanelDropDownShown=!1;const catalogueDropDown=document.querySelector(".menu-panel-catalogues-dropdown");function showCatalogueDropDown(e){e||(e=window.event),e.preventDefault(),catalogueDropDown&&(catalogueDropDown.style.display=isCatalogueMenuPanelDropDownShown?"none":"block",isCatalogueMenuPanelDropDownShown=!isCatalogueMenuPanelDropDownShown)}document.addEventListener("DOMContentLoaded",function(){fetchCategories(),window.addEventListener("resize",fetchCategories);let e=document.querySelector(".dropdown-toggle"),a=document.querySelector(".dropdown-menu"),o=!0;e&&a&&e.addEventListener("click",function(e){e.preventDefault(),a.style.display="none"===a.style.display||o?"block":"none",o=!1});let l=document.querySelector(".catalogueDropdown-toggle"),t=document.querySelector(".catalogueDropdown-menu");l&&t&&(l.addEventListener("click",function(e){e.preventDefault(),t.classList.toggle("show")}),document.addEventListener("click",function(e){l.contains(e.target)||t.contains(e.target)||t.classList.remove("show")}))});