  <!-- ============================================================== -->
  <!-- End Container fluid  -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- footer -->
  <!-- ============================================================== -->
  <footer class="footer text-center text-dark">
    All Rights Reserved by ATGPUG. Designed and Developed by <a href="https://assetplusconsulting.com/">Assetplus Consulting </a>.
  </footer>
  <!-- ============================================================== -->
  <!-- End footer -->
  <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Page wrapper  -->
  <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Wrapper -->
  <!-- ============================================================== -->
  <!-- End Wrapper -->
  <!-- ============================================================== -->
  <!-- All Jquery -->
  <!-- ============================================================== -->

  <!-- <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> -->
  <!-- apps -->
  <!-- apps -->
  <!-- <script src="../dist/js/app-style-switcher.js"></script> -->
  <script src="../dist/js/feather.min.js"></script>
  <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../dist/js/custom.min.js"></script>
  <script>
    // $(document).ready(function() {
      
    // })


    // to hide and unhide while window size is small and large respectively
    $(window).resize(function() {
      if($(window).width() <= 767){
        $('aside').addClass('hide')
      } else {
        $('aside').removeClass('hide')
      }
      // console.log('window was resized');
    });

    $('#menu').click(function() {
        // console.log('menu clicked')
        $('aside').toggleClass('hide')
    })

    $('#login_sidebar_btn').click(function() {
      // console.log("login_sidebar_btn clicked")
      $('#login_popup').css("display", "flex")

      //to stop scroll of background
      $('body').css('overflow', 'hidden')
    })

    $('#close_popup').click(function() {
      // console.log('close clicked')
      $('#login_popup').css("display", "none")

      //to start scroll of background
      $('body').css('overflow', 'visible')
    })

    function hide_login_popup() {
      $('#login_popup').css("display", "none")
      //to start scroll of background
      $('body').css('overflow', 'visible')
    }

    // reset form data
  function reset_form_data(form_id) {
      $(`${form_id}`)[0].reset();
  }
    
  // calling api to store form data
  function store_logn_data(form_data_object, api_url, form_id) {
      console.log('func form data', form_data_object)
      $.ajax({
          url: api_url,
          type: 'post',
          data: form_data_object,
          success: function(response) {
              console.log('response', response)
              const data = JSON.parse(response)
              console.log(data)
              alert(data['message'])
              console.log(data['message'])
              if(data['error'] === false) {
                window.location.href = "index.php"
                  // reset_form_data(form_id)

                  // hide_login_popup()
                  // console.log('close clicked')
              }
          }
      })
  }

    //to submit login credentials
    $('#login_submit').click(function(){
    let form_arr = $('#login_form').serializeArray()
    console.log(form_arr)
    var form_obj={}
    for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
    }
    const api_url = "../CNG_API/authentication.php?apicall=login"
    console.log(form_obj)
    store_data(form_obj, api_url, '#login_form')
})


  </script>
  <!--This page JavaScript -->
  <!-- <script src="../assets/extra-libs/c3/d3.min.js"></script>
  <script src="../assets/extra-libs/c3/c3.min.js"></script>
  <script src="../assets/libs/chartist/dist/chartist.min.js"></script>
  <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
  <script src="../assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
  <script src="../assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script> -->
  <!-- <script src="../dist/js/pages/dashboards/dashboard1.min.js"></script> -->