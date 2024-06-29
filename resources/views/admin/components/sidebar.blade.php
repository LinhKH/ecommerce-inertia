
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      @if(site_settings()->site_logo != '')
        <img src="{{asset('site/'.site_settings()->site_logo)}}" class="bg-white p-2" width="100%" alt="{{site_settings()->site_name}}">
      @else
      <span class="brand-text font-weight-light">{{site_settings()->site_name}}</span>
      @endif
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="javascript:void(0)" class="d-block">{{session()->get('admin_name')}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('admin/dashboard')}}" class="nav-link {{(Request::path() == 'admin/dashboard')? 'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{(Request::path() == 'admin/products' || Request::path() == 'admin/category' || Request::path() == 'admin/sub-category' || Request::path() == 'admin/brand' || Request::path() == 'admin/colors' || Request::path() == 'admin/attribute' || Request::path() == 'admin/attribute-values' || Request::path() == 'admin/tax')? 'menu-open':''}}">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Products <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('admin/products')}}" class="nav-link {{(Request::path() == 'admin/products')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>  
                  <p>All Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/category')}}" class="nav-link {{(Request::path() == 'admin/category')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>  
                  <p>Category</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{url('admin/brand')}}" class="nav-link {{(Request::path() == 'admin/brand')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Brand</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/colors')}}" class="nav-link {{(Request::path() == 'admin/colors')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Colors</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/attribute')}}" class="nav-link {{(Request::path() == 'admin/attribute')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Attribute Sets</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/attribute-values')}}" class="nav-link {{(Request::path() == 'admin/attribute-values')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Attribute Values</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/flash-deals')}}" class="nav-link {{(Request::path() == 'admin/flash-deals')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Flash Deals</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/tax')}}" class="nav-link {{(Request::path() == 'admin/tax')? 'active':''}}">
                  <i class="nav-icon fas fa-wallet"></i>
                  <p>
                    Tax
                  </p>
                </a>
              </li>
            </ul> 
          </li>
          <li class="nav-item">
            <a href="{{url('admin/orders')}}" class="nav-link {{(Request::path() == 'admin/orders')? 'active':''}}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Orders
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{(Request::path() == 'admin/countries' || Request::path() == 'admin/states' || Request::path() == 'admin/cities')? 'menu-open':''}}">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-shipping-fast"></i>
              <p>Shipping <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('admin/countries')}}" class="nav-link {{(Request::path() == 'admin/countries')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Available Countries</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/states')}}" class="nav-link {{(Request::path() == 'admin/states')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Available States</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/cities')}}" class="nav-link {{(Request::path() == 'admin/cities')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Available Cities</p>
                </a>
              </li>
            </ul> 
          </li>
          <li class="nav-item">
            <a href="{{url('admin/users')}}" class="nav-link {{(Request::path() == 'admin/users')? 'active':''}}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/reviews')}}" class="nav-link {{(Request::path() == 'admin/reviews')? 'active':''}}">
              <i class="nav-icon fas fa-star"></i>
              <p>
                Reviews
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{url('admin/pages')}}" class="nav-link {{(Request::path() == 'admin/pages')? 'active':''}}">
              <i class="nav-icon fas fa-file-word"></i>
              <p>
                Pages
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/payment-method')}}" class="nav-link {{(Request::path() == 'admin/payment-method')? 'active':''}}">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Payment Methods
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{(Request::path() == 'admin/product-sale' || Request::path() == 'admin/product-stock')? 'menu-open':''}}">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Reports <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('admin/product-sale')}}" class="nav-link {{(Request::path() == 'admin/product-sale')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Products Sale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/product-stock')}}" class="nav-link {{(Request::path() == 'admin/product-stock')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Products Stock</p>
                </a>
              </li>
            </ul> 
          </li>
          <li class="nav-item has-treeview {{(Request::path() == 'admin/general-settings' || Request::path() == 'admin/profile-settings' || Request::path() == 'admin/banner')? 'menu-open':''}}">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('admin/banner')}}" class="nav-link {{(Request::path() == 'admin/banner')? 'active':''}}">
                  <i class="nav-icon fas fa-images"></i>
                  <p>
                    Banner Settings
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/general-settings')}}" class="nav-link {{(Request::path() == 'admin/general-settings')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>General Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/profile-settings')}}" class="nav-link {{(Request::path() == 'admin/profile-settings')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Profile Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('admin/social-settings')}}" class="nav-link {{(Request::path() == 'admin/social-settings')? 'active bg-primary':''}}">
                  <i class="nav-icon far fa-circle"></i> 
                  <p>Social Links Settings</p>
                </a>
              </li>
            </ul> 
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
