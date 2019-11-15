var appDonut = angular.module('donutModule', []);

(function(app){
    "use strict";
    app.controller("myController", function($scope, $http, $interval){
        // declare vars
        ///////////////////////////////////////////////////////////////
        $scope.order = {
            totalqty: 0,
            list: []
        };
        $scope.proceed_window = false;
        $scope.thankyou_section = false;

        // http requests
        ////////////////////////////////////////////////////////////////

        // get product lists
        $scope.get_products = function(){
            return $http.get('list.json').then(function(response){
                $scope.products = response.data.data;
            }, function(error){
                alert(`HTTP Error Occured:  ${error.status} ${error.statusText}`);
            });
        }

        // post customer and order data
        $scope.checkout = function(){
            var obj = {
                order: $scope.order,
                customer: $scope.customer
            }

            return $http.post('process.php', obj)
                    .then(function(response){
                        if(response.data.success){
                            $scope.thankyou_section = true;
                            $scope.order_num = response.data.order_num;
                        }else if(response.data.error){
                            $scope.response_message = response.data.message;
                        }
                    }, function(error){
                        alert(`HTTP Error Occured:  ${error.status} ${error.statusText}`);
                    })
        }

        // other funcs
        ///////////////////////////////////////////////////////////////
        $scope.product_increase = function(row){
            row.selected = row.selected + 1;
        }

        $scope.product_decrease = function(row){
            row.selected = row.selected - 1;
        }

        $scope.add_cart = function(item){
            // check if qty still available
            var index = $scope.products.map(e => e.productID).indexOf(item.productID);
            if($scope.products[index].qtyAvailable >= item.selected){
                // add to cart
                $scope.order.totalqty = $scope.order.totalqty + item.selected;
                // check if product id exists then just update
                var found = $scope.order.list.map(e => e.productID).indexOf(item.productID);
                if(found === -1){                        
                    // push item to the order list if not found
                    var obj = {
                        productID: item.productID,
                        productName: item.productName,
                        qtyOrdered: item.selected,
                        price: item.price
                    };
                    $scope.order.list.push(obj);
                }else{  // update
                    $scope.order.list[found].qtyOrdered = $scope.order.list[found].qtyOrdered + item.selected;
                }

                // update products list qty available
                var index = $scope.products.map(e => e.productID).indexOf(item.productID);
                $scope.products[index].qtyAvailable = $scope.products[index].qtyAvailable - item.selected;

                // return to orig selected state
                $scope.products[index].selected = 1;
            }else{
                // adjust qty available
                $scope.products[index].selected = $scope.products[index].qtyAvailable;
                $scope.products[index].message = `Only ${$scope.products[index].qtyAvailable} in stock`;
                return false;
            }
            
    
        }

        $scope.show_welcome_display = function(){
            if($scope.order.list.length === 0){
                $('#exampleModal').modal('toggle');
            }
        }

        $scope.proceed_process = function(){
            $scope.proceed_window = true;
        }

        $scope.product_calc_sum = function(){
            return $scope.order.list.reduce((a, b) => a + (b["price"] * b["qtyOrdered"] || 0), 0);
        }

        $('#exampleModal').on('hidden.bs.modal', function () {
            // clear/reset some vars 
            console.log("test");
            $scope.proceed_window = false;
            $scope.thankyou_section = false;
            $scope.response_message = '';
        });

        // run init
        $scope.get_products().then($scope.show_welcome_display());

    });
})(appDonut);