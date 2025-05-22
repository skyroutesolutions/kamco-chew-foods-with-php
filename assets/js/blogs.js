const blogsGrid=document.getElementById("blogs-grid");async function fetchBlogs(){try{let e=await fetch("backend/get_blog.php"),t=await e.text(),a=JSON.parse(t);blogsGrid&&(console.log(a),a.forEach((e,t)=>{blogsGrid.innerHTML+=`
      <div id="blog${t+1}" class="individual-blog-wrapper">
            <a href="blog?Name=${e.name}">
            <div style="background-color: red; border-radius: 20px;" class="features-item panel vstack gap-4 xl:gap-6 px-4 py-6 xl:px-5 xl:py-8 border border-2 border-black contrast-shadow-md text-black bg-white rotate-1">
            <div class="feature-item-image">
                  <img style="height: 300px; width: 280px; object-fit: cover;" class="image mx-auto" src="../dark/backend/uploads/blogs/${e.images[0]||"default.jpg"}" alt="${e.name}">
            </div>
            <div class="feature-item-content">
                  <h6 style="color: #E31E23; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif !important;" class="h5 xl:h6">
                  ${e.name}
                  </h6>
                  <p>${e.description.substring(0,100)}</p>
            </div>
            </div>
            </a>
      </div>`}))}catch(r){console.error("Failed to fetch blogs:",r)}}fetchBlogs();const aboutElement=document.getElementById("about"),rollis1=document.querySelector("#rollis1"),rollis2=document.querySelector("#rollis2");gsap.to(rollis1,{scrollTrigger:{trigger:aboutElement,start:"top 10%",end:"60% center",scrub:2},top:"40%",right:"100%",ease:"power2.inOut"}),gsap.to(rollis2,{scrollTrigger:{trigger:aboutElement,start:"top 10%",end:"60% center",scrub:2},bottom:"40%",left:"100%",ease:"power2.inOut"});const queryString=window.location.search,urlParams=new URLSearchParams(queryString),blogName=urlParams.get("Name");blogName&&fetchBlogDetails(blogName);const getSchema=urlParams.get("schema");async function fetchBlogDetails(e){try{document.querySelector(".left h2").textContent="Loading...",document.querySelector(".left p").textContent="",document.querySelector(".right").innerHTML=`
<div class="loading-spinner"></div>
<style>
.loading-spinner {
width: 50px;
height: 50px;
border: 5px solid #f3f3f3;
border-top: 5px solid #E31E23;
border-radius: 50%;
animation: spin 1s linear infinite;
margin: 20px auto;
}
@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
</style>
`;let t=await fetch(`backend/get_blog_details.php?Name=${encodeURIComponent(e)}`);if(!t.ok)throw Error(`HTTP error! status: ${t.status}`);let a=await t.json();if(a.error)throw Error(a.error);if(a&&a.name){document.querySelector(".left h2").textContent=a.name,document.querySelector(".left p").textContent=a.description;let r=document.querySelector(".right");if(r.innerHTML=`
<div class="blog-gallery">
${a.images&&a.images.length>0?a.images.map((e,t)=>`
<img 
src="../dark/backend/uploads/blogs/${e||"default.jpg"}"
alt="${a.name} - Image ${t+1}"
class="gallery-image"
loading="lazy"
>
`).join(""):'<img src="../assets/images/products/default.jpg" alt="Default blog image">'}
</div>
<div class="recent-blogs">
<h2>Recent Blogs</h2>
<!-- Will be populated by fetchRecentBlogs() -->
</div>
`,a.images&&a.images.length>1){let l=r.querySelector(".blog-gallery");l.insertAdjacentHTML("beforeend",`
<div class="gallery-nav">
${a.images.map((e,t)=>`
<button class="gallery-dot ${0===t?"active":""}" 
data-index="${t}"></button>
`).join("")}
</div>
`)}let o=document.createElement("style");o.textContent=`
.blog-gallery {
display: flex;
flex-direction: column;
gap: 20px;
margin-bottom: 30px;
}
.blog-gallery img {
max-width: 100%;
height: auto;
border-radius: 10px;
box-shadow: 0 4px 8px rgba(0,0,0,0.1);
transition: transform 0.3s ease;
}
.blog-gallery img:hover {
transform: scale(1.02);
}
.gallery-nav {
display: flex;
justify-content: center;
gap: 10px;
margin-top: 10px;
}
.gallery-dot {
width: 10px;
height: 10px;
border-radius: 50%;
background-color: #E31E23;
border: none;
cursor: pointer;
transition: background-color 0.3s;
}
.gallery-dot.active {
background-color: #fff;
}
.gallery-dot:hover {
background-color: #ccc;
}
.error-message {
color: #E31E23;
padding: 20px;
text-align: center;
font-weight: bold;
}
`,document.head.appendChild(o),await fetchRecentBlogs()}else throw Error("Blog details not found!")}catch(i){console.error("Failed to fetch blog details:",i),document.querySelector(".right").innerHTML=`
<div class="error-message">
Error loading blog: ${i.message}
</div>
`}}async function fetchRecentBlogs(){try{let e=await fetch("backend/get_recent_blogs.php"),t=await e.json(),a=document.querySelector(".recent-blogs");t.length>0?a.innerHTML=`
<h2>Recent Blogs</h2>
${t.map(e=>`
<div>
<a href="blog?Name=${encodeURIComponent(e.name)}">
  <img src="../dark/backend/uploads/blogs/${e.image||"default.jpg"}" alt="${e.name}">
</a>
<div>
  <p>${e.name}</p>
</div>
</div>
`).join("")}
`:a.innerHTML="<p>No recent blogs available.</p>"}catch(r){(recentBlogsContainer=document.querySelector(".recent-blogs"))&&(recentBlogsContainer.innerHTML="<p>Error loading recent blogs.</p>")}}"dark"===getSchema?setDarkMode(1):"light"===getSchema&&setDarkMode(0),"faq"===window.location.href.split("#")[1]&&(document.getElementsByClassName("animated-element")[0].style.display="none"),fetchRecentBlogs();