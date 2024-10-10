document.addEventListener("DOMContentLoaded", () => {
      let body = document.body;
      let profile = document.querySelector('.profile');
      let searchForm = document.querySelector('.header .flex .search-form');
      let sideBar = document.querySelector('.side-bar');
  
      document.querySelector('#user-btn').onclick = () => {
          if (profile) {
              profile.classList.toggle('active');
              if (searchForm) {
                  searchForm.classList.remove('active');
              }
          }
      };
  
      document.querySelector('#search-btn').onclick = () => {
          if (searchForm) {
              searchForm.classList.toggle('active');
              if (profile) {
                  profile.classList.remove('active');
              }
          }
      };
  
      document.querySelector('#menu-btn').onclick = () => {
          if (sideBar) {
              sideBar.classList.toggle('active');
          }
          body.classList.toggle('active');
      };
  
      window.onscroll = () => {
            if (profile) {
                profile.classList.remove('active');
            }
        
            if (searchForm) {
                searchForm.classList.remove('active');
            }
        
            if (window.innerWidth < 1200) {
                if (sideBar) {
                    sideBar.classList.remove('active');
                } else {
                    console.error('Sidebar element not found');
                }
            }
      };
        
  });
  
/*----------------counter--------------*/
(() => {
      const counter = document.querySelectorAll('.counter');
      //convent to array
      const array = Array.from(counter);
      //select array element
      array.map((item) => {
            //data layer

            let counterInnerText = item.textContent;
            item.textContent = 0;
            let count = 1;
            let speed = item.dataset.speed / counterInnerText;
            function counterUp() {
                  item.textContent = count++;
                  if (counterInnerText < count) {
                        clearInterval(stop);
                  }
            }
            const stop = setInterval(() => {
                  counterUp();
            }, speed)
      })
})()