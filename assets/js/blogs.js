// Fetch blogs from backend
const blogsGrid = document.getElementById("blogs-grid");

async function fetchBlogs() {
  try {
    const response = await fetch("backend/get_blog.php");
    const text = await response.text();         // Get raw text response
    // console.log("Raw Response:", text);          // Log raw response

    const blogs = JSON.parse(text);              // Parse JSON
    // console.log("Parsed Blogs:", blogs);         // Log parsed JSON

    if (blogsGrid) {
      blogsGrid.innerHTML = blogs.map((blog) => `
        <a href="blog.html?Name=${blog.name}">
          <div style="border-radius: 20px;" class="features-item panel vstack gap-4 xl:gap-6 px-4 py-6 xl:px-5 xl:py-8 border border-2 border-black contrast-shadow-md text-black bg-white rotate-1">
            <div class="feature-item-image">
              <img style="height: 300px; width: 280px; object-fit: cover;" class="image mx-auto" src="http://localhost/kamco/Dark/backend/uploads/blogs/${blog.images[0] || 'default.jpg'}" alt="${blog.name}">
            </div>
            <div class="feature-item-content">
              <h6 style="color: #E31E23; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif !important;" class="h5 xl:h6">
                ${blog.name}
              </h6>
              <p>${blog.description.substring(0, 100)}</p>
            </div>
          </div>
        </a>`).join('');
    }
  } catch (error) {
    console.error("Failed to fetch blogs:", error);
  }
}

// Call the function to load blogs
fetchBlogs();

// Scroll animations
const aboutElement = document.getElementById("about");
const rollis1 = document.querySelector("#rollis1");
const rollis2 = document.querySelector("#rollis2");

gsap.to(rollis1, {
  scrollTrigger: {
    trigger: aboutElement,
    start: "top 10%",
    end: "60% center",
    scrub: 2,
  },
  top: "40%",
  right: "100%",
  ease: "power2.inOut",
});

gsap.to(rollis2, {
  scrollTrigger: {
    trigger: aboutElement,
    start: "top 10%",
    end: "60% center",
    scrub: 2,
  },
  bottom: "40%",
  left: "100%",
  ease: "power2.inOut",
});



// Extract blog name from URL
// const queryString = window.location.search;
// const urlParams = new URLSearchParams(queryString);
const blogName = urlParams.get("Name");
if (blogName) {
  fetchBlogDetails(blogName);
} else {
  console.error("Blog name not found in URL");
}

// Function to fetch blog details by name
async function fetchBlogDetails(name) {
  try {
    const response = await fetch(`backend/get_blog_details.php?Name=${encodeURIComponent(name)}`);
    const text = await response.text();          // Get raw text response
    
    console.log("Raw Response:", text);           // Log raw response

    const blog = JSON.parse(text);                // Parse JSON
    console.log("Parsed Blog Details:", blog);    // Log parsed JSON

    if (blog && blog.name) {
      // Update blog details in HTML
      document.querySelector(".left h2").textContent = blog.name;
      document.querySelector(".left p").textContent = blog.description;
      document.querySelector(".right img").src = blog.images[0] 
      ? `http://localhost/kamco/Dark/backend/uploads/blogs/${blog.images[0]}`
      : "../assets/images/products/default.jpg";
        } else {
      console.error("Blog details not found!");
    }
  } catch (error) {
    console.error("Failed to fetch blog details:", error);
  }
}


// Fetch and display recent blogs
async function fetchRecentBlogs() {
  try {
      const response = await fetch("backend/get_recent_blogs.php");
      const blogs = await response.json();

      const recentBlogsContainer = document.querySelector(".recent-blogs");
      if (blogs.length > 0) {
          recentBlogsContainer.innerHTML = `
              <h2>Recent Blogs</h2>
              ${blogs.map(blog => `
                  <div>
                      <a href="blog.html?Name=${encodeURIComponent(blog.name)}">
                          <img src="http://localhost/kamco/Dark/backend/uploads/blogs/${blog.image}" alt="${blog.name}">
                      </a>
                      <div>
                          <p>${blog.name}</p>
                      </div>
                  </div>
              `).join('')}
          `;
      } else {
          recentBlogsContainer.innerHTML = "<p>No recent blogs available.</p>";
      }
  } catch (error) {
      console.error("Failed to fetch recent blogs:", error);
  }
}

// Call function to load recent blogs
fetchRecentBlogs();
