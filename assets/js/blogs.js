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
                  console.log(blogs)
                  blogs.forEach((blog, index) => {
                        blogsGrid.innerHTML += `
                              <div id="blog${index + 1}" class="individual-blog-wrapper">
                                    <a href="blog?Name=${blog.name}">
                                    <div style="background-color: red; border-radius: 20px;" class="features-item panel vstack gap-4 xl:gap-6 px-4 py-6 xl:px-5 xl:py-8 border border-2 border-black contrast-shadow-md text-black bg-white rotate-1">
                                    <div class="feature-item-image">
                                          <img style="height: 300px; width: 280px; object-fit: cover;" class="image mx-auto" src="../dark/backend/uploads/blogs/${blog.images[0] || 'default.jpg'}" alt="${blog.name}">
                                    </div>
                                    <div class="feature-item-content">
                                          <h6 style="color: #E31E23; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif !important;" class="h5 xl:h6">
                                          ${blog.name}
                                          </h6>
                                          <p>${blog.description.substring(0, 100)}</p>
                                    </div>
                                    </div>
                                    </a>
                              </div>`
                  });
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
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const blogName = urlParams.get("Name");
if (blogName) {
      fetchBlogDetails(blogName);
} else {
      console.error("Blog name not found in URL");
}

// Function to fetch blog details by name
async function fetchBlogDetails(name) {
      try {
            // Show loading state
            document.querySelector(".left h2").textContent = "Loading...";
            document.querySelector(".left p").textContent = "";
            document.querySelector(".right").innerHTML = `
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
    `;

            const response = await fetch(`backend/get_blog_details.php?Name=${encodeURIComponent(name)}`);

            if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
            }

            const blog = await response.json();

            if (blog.error) {
                  throw new Error(blog.error);
            }

            if (blog && blog.name) {
                  // Update blog details in HTML
                  document.querySelector(".left h2").textContent = blog.name;
                  document.querySelector(".left p").textContent = blog.description;

                  // Create image gallery
                  const rightPanel = document.querySelector(".right");
                  rightPanel.innerHTML = `
        <div class="blog-gallery">
          ${blog.images && blog.images.length > 0 ?
                              blog.images.map((image, index) => `
              <img 
                src="../dark/backend/uploads/blogs/${image || 'default.jpg'}"
                alt="${blog.name} - Image ${index + 1}"
                class="gallery-image"
                loading="lazy"
              >
            `).join('')
                              : `<img src="../assets/images/products/default.jpg" alt="Default blog image">`
                        }
        </div>
        <div class="recent-blogs">
          <h2>Recent Blogs</h2>
          <!-- Will be populated by fetchRecentBlogs() -->
        </div>
      `;

                  // Add gallery navigation if multiple images
                  if (blog.images && blog.images.length > 1) {
                        const gallery = rightPanel.querySelector('.blog-gallery');
                        gallery.insertAdjacentHTML('beforeend', `
          <div class="gallery-nav">
            ${blog.images.map((_, index) => `
              <button class="gallery-dot ${index === 0 ? 'active' : ''}" 
                      data-index="${index}"></button>
            `).join('')}
          </div>
        `);
                  }

                  // Add gallery styles
                  const style = document.createElement('style');
                  style.textContent = `
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
      `;
                  document.head.appendChild(style);

                  // Load recent blogs
                  await fetchRecentBlogs();
            } else {
                  throw new Error("Blog details not found!");
            }
      } catch (error) {
            console.error("Failed to fetch blog details:", error);
            document.querySelector(".right").innerHTML = `
      <div class="error-message">
        Error loading blog: ${error.message}
      </div>
    `;
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
                      <a href="blog?Name=${encodeURIComponent(blog.name)}">
                          <img src="../dark/backend/uploads/blogs/${blog.image || 'default.jpg'}" alt="${blog.name}">
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
            document.querySelector(".recent-blogs").innerHTML = "<p>Error loading recent blogs.</p>";
      }
}

// Call function to load recent blogs
fetchRecentBlogs();
