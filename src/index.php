<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="node_modules/angular/angular.min.js"></script>
<script src="dist/js/scripts.js"></script>
<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">

<!doctype html>
<html lang="en">
  <head>
    <title>Delicious Donuts</title>
  </head>

  <body  ng-app="donutModule" ng-controller="myController">
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
      <h5 class="my-0 mr-md-auto font-weight-normal">Delicious Donuts</h5>
      <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="#">Welcome Guest!</a>
      </nav>
      <a class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModal">Cart {{ order.totalqty }}</a>
    </div>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-4">DonutLicious</h1>
      <p class="lead">Try our gluten free, dairy free and low carb Donuts! Hurry before it's gone.</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="card-deck mb-3 text-center">
                <div class="card mb-4 box-shadow" ng-repeat="p in products">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">{{ p.productName }}</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">${{ p.price }}</h1>
                        <img src="dist/img/{{ p.image }}" class="img-thumbnail">
                        <div ng-show="p.selected === p.qtyAvailable" class="text-danger">Max available quantity has been reached. </div>
                        <div ng-show="p.qtyAvailable === 0" class="text-danger">Not available </div>
                        <div ng-if="p.qtyAvailable != 0">
                            <button ng-disabled="p.selected == 1" ng-click="product_decrease(p)">-</button> {{ p.selected }} <button ng-disabled="p.selected == p.qtyAvailable" ng-click="product_increase(p)">+</button>
                            <button type="button" ng-click="add_cart(p)" class="btn btn-md btn-block btn-outline-success">Add to cart</button>
                        </div>
                                            
                    </div>
                </div>
            </div>
        </div>
      <footer class="pt-4 my-md-5 pt-md-5 border-top">
      </footer>
    </div>
    <section>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Shopping Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 ng-if="order.list.length == 0">Your cart looking so skinny! You should add some donuts :) </h2>
                <div ng-if="order.list.length > 0 && !proceed_window && !thankyou_section">
                    <table class="table responsive">
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                        <tr ng-repeat="o in order.list">
                            <td>{{ o.productName }}</td>
                            <td>{{ o.qtyOrdered }}</td>
                            <td>{{ o.price }}</td>
                            <td>${{ o.price * o.qtyOrdered }}</td>
                        </tr>
                    </table>
                    <h3 class="text-right">Total: ${{ product_calc_sum() }}</h3>
                </div>
                <div id="userFormSection" ng-show="proceed_window">
                    <form name="checkoutForm" id="checkoutForm">
                        <h4 class="mb-3">Customer Info</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" ng-model="customer.firstname" required>
                                <div class="text-danger" ng-show="checkoutForm.firstname.$error.required && checkoutForm.firstname.$touched">
                                Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname">Last name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" ng-model="customer.lastname" required>
                                <div class="text-danger" ng-show="checkoutForm.lastname.$error.required && checkoutForm.lastname.$touched">
                                Valid last name is required.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email </label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="you@example.com" ng-model="customer.emailaddress" required>
                            <div class="text-danger" ng-show="checkoutForm.email.$error.required && checkoutForm.email.$touched">
                                Please enter a valid email address for updates.
                            </div>
                        </div>
                        <div class="row alert alert-danger" ng-if="response_message">{{ response_message }}</div>
                    </form>

                <div id="thankYouSection" ng-if="thankyou_section">
                    <h3>Order# {{ order_num }}</h3>
                    <h2>Thanks for shopping with us {{ customer.firstname }}!</h2>
                    <p>Will send you an email when your order is ready for pickup. </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" ng-if="order.list.length > 0 && !proceed_window" ng-click="proceed_process()">Proceed Checkout</button>
                <button type="button" class="btn btn-primary" ng-show="proceed_window && !thankyou_section" ng-disabled="!checkoutForm.$valid" ng-click="checkout()">Submit Order</button>
            </div>
            </div>
        </div>
        </div>
    </section>
  </body>
</html>
