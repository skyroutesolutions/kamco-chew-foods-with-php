// Generate static catalogue HTML
function getStaticCatalogueHTML() {
    return `
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
    `;
}

// Function to check if mobile view
function isMobileView() {
    return window.innerWidth <= 768;
}

// Fetch categories from appropriate backend endpoint
async function fetchCategories() {
    try {
        const endpoint = isMobileView() 
              ? 'backend/fetch_categories_mobile.php' 
              : 'backend/fetch_categories_desktop.php';
            
        const response = await fetch(endpoint);
        if (!response.ok) throw new Error('Network response was not ok');
        const categories = await response.json();
        
        const dropdownHTML = [
            '<li><a href="shop.html">All Product</a></li>',
            ...categories.map(cat => `
                <li><a href="shop.html?category=${encodeURIComponent(cat.name)}">${cat.name}</a></li>
            `)
        ].join('');

        if (isMobileView()) {
            const mobileProducts = document.querySelector('.menu-panel-products-dropdown');
            const mobileCatalog = document.querySelector('.menu-panel-catalogues-dropdown');
            
            if (mobileProducts) mobileProducts.innerHTML = dropdownHTML;
            if (mobileCatalog) mobileCatalog.innerHTML = getStaticCatalogueHTML();
        } else {
            const desktopProducts = document.querySelector('.dropdown-menu');
            const desktopCatalog = document.querySelector('.catalogueDropdown-menu');
            
            if (desktopProducts) desktopProducts.innerHTML = dropdownHTML;
            if (desktopCatalog) desktopCatalog.innerHTML = getStaticCatalogueHTML();
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

// Mobile dropdown toggle functions
function showProductsDropDown(event) {
    if (!event) event = window.event;
    event.preventDefault();
    const dropdown = document.querySelector('.menu-panel-products-dropdown');
    if (dropdown) {
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
}

let isCatalogueMenuPanelDropDownShown = false;
const catalogueDropDown = document.querySelector('.menu-panel-catalogues-dropdown');

function showCatalogueDropDown(event) {
    if (!event) event = window.event;
    event.preventDefault();
    if (catalogueDropDown) {
        catalogueDropDown.style.display = isCatalogueMenuPanelDropDownShown ? "none" : "block";
        isCatalogueMenuPanelDropDownShown = !isCatalogueMenuPanelDropDownShown;
    }
}

// Initialize dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();
    window.addEventListener('resize', fetchCategories);

    const productsToggle = document.querySelector('.dropdown-toggle');
    const productsMenu = document.querySelector('.dropdown-menu');
    
    if (productsToggle && productsMenu) {
        productsToggle.addEventListener('click', function(e) {
            e.preventDefault();
            productsMenu.style.display = productsMenu.style.display === 'none' ? 'block' : 'none';
        });
    }

    const catalogueDropdownToggle = document.querySelector(".catalogueDropdown-toggle");
    const catalogueDropdownMenu = document.querySelector(".catalogueDropdown-menu");

    if (catalogueDropdownToggle && catalogueDropdownMenu) {
        catalogueDropdownToggle.addEventListener("click", function(e) {
            e.preventDefault();
            catalogueDropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", function(e) {
            if (!catalogueDropdownToggle.contains(e.target) && !catalogueDropdownMenu.contains(e.target)) {
                catalogueDropdownMenu.classList.remove("show");
            }
        });
    }
});
