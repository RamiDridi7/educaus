# EducaUS
Please Login with @Dica-x github account to have access to the pull/push branch

Template Files : https://drive.google.com/file/d/1m2xOixlDaloiPnlXzytIXacILLL_YGaK/view?usp=sharing

# Base.html.twig contains layout ( stylesheet/javascript links ) 
    <!-- preloader -->
    <!-- Header -->
        <!-- Start main-content -->
        <!-- End main-content -->
    <!-- Footer -->
    
# /!\ Please Rememeber to only commit a code without errors /!\

# index.html.twig contains  only content of home page
    <!-- Section: About  -->
    <!-- Section: Divider -->
    <!-- Section: COURSES -->
    <!-- Section: Why Choose Us -->
    <!-- Divider: Funfact -->
    <!-- Section: team -->
    <!-- Section: Gallery -->
    <!-- Section: Client Say -->
    <!-- Section: blog -->
    <!-- Divider: Call To Action -->
# To Add new Page follow these steps : 
    run command : - Symfony make:controller
                  - After naming it, a new twig file will be created under templates/
                  - Open .html file you want to integrate
                  - Look for <div class="main-content">
                  - navigate to templates/NAME.html.twig
                  - Past the CONTENT of the DIV  in {% block body %} {% end block %} 
                  - Create a route and check your work 
   
    
 
 

