@extends('front.layouts.app',['page'=>'Cart'])

@section('content')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0"><a href="{{ url('/') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                    href="cart.html">Cart</a> <span class="mx-2 mb-0">/</span> <strong
                    class="text-black">Checkout</strong></div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <form action="{{ route('order.create') }}" method="post">
            @csrf
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="border p-4 rounded" role="alert">
                        Returning customer? <a href="#">Click here</a> to login
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group">
                            <label for="country" class="text-black">Country <span class="text-danger">*</span></label>
                            <select id="country" class="form-control" name="country" required>
                                <option value="" hidden>Select a country</option>
                                <option value="Indonesia">Indonesia</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name" class="text-black">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="company_name" class="text-black">Company Name </label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="address" class="text-black">Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Street address" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)"
                                name="address2" required>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="province" class="text-black">Province <span
                                        class="text-danger">*</span></label>
                                <select id="province" class="form-control" name="province" required>
                                    <option value="" hidden>Select a province</option>
                                    <option value="jakarta">Jakarta</option>
                                    <option value="jawa_barat">Jawa Barat</option>
                                    <option value="jawa_tengah">Jawa Tengah</option>
                                    <option value="jawa_timur">Jawa Timur</option>
                                </select>
                                {{-- <label for="province" class="text-black">Province <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="province" name="province"> --}}
                            </div>
                            <div class="col-md-6">
                                <label for="zip" class="text-black">Posta / Zip <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="email" class="text-black">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Phone Number" required>
                            </div>
                        </div>

                        {{-- <div class="form-group">
                        <label for="c_create_account" class="text-black" data-toggle="collapse"
                            href="#create_an_account" role="button" aria-expanded="false"
                            aria-controls="create_an_account"><input type="checkbox" value="1" id="c_create_account">
                            Create an account?</label>
                        <div class="collapse" id="create_an_account">
                            <div class="py-2">
                                <p class="mb-3">Create an account by entering the information below. If you are a
                                    returning customer please login at the top of the page.</p>
                                <div class="form-group">
                                    <label for="c_account_password" class="text-black">Account Password</label>
                                    <input type="email" class="form-control" id="c_account_password"
                                        name="c_account_password" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div> --}}


                        {{-- <div class="form-group">
                        <label for="c_ship_different_address" class="text-black" data-toggle="collapse"
                            href="#ship_different_address" role="button" aria-expanded="false"
                            aria-controls="ship_different_address"><input type="checkbox" value="1"
                                id="c_ship_different_address"> Ship To A Different Address?</label>
                        <div class="collapse" id="ship_different_address">
                            <div class="py-2">

                                <div class="form-group">
                                    <label for="c_diff_country" class="text-black">Country <span
                                            class="text-danger">*</span></label>
                                    <select id="c_diff_country" class="form-control">
                                        <option value="1">Select a country</option>
                                        <option value="2">bangladesh</option>
                                        <option value="3">Algeria</option>
                                        <option value="4">Afghanistan</option>
                                        <option value="5">Ghana</option>
                                        <option value="6">Albania</option>
                                        <option value="7">Bahrain</option>
                                        <option value="8">Colombia</option>
                                        <option value="9">Dominican Republic</option>
                                    </select>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="name" class="text-black">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_diff_companyname" class="text-black">Company Name </label>
                                        <input type="text" class="form-control" id="c_diff_companyname"
                                            name="c_diff_companyname">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_diff_address" class="text-black">Address <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_address"
                                            name="c_diff_address" placeholder="Street address">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control"
                                        placeholder="Apartment, suite, unit etc. (optional)">
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="c_diff_state_country" class="text-black">State / Country <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_state_country"
                                            name="c_diff_state_country">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="c_diff_postal_zip" class="text-black">Posta / Zip <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_postal_zip"
                                            name="c_diff_postal_zip">
                                    </div>
                                </div>

                                <div class="form-group row mb-5">
                                    <div class="col-md-6">
                                        <label for="c_diff_email_address" class="text-black">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_email_address"
                                            name="c_diff_email_address">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="c_diff_phone" class="text-black">Phone <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_phone" name="c_diff_phone"
                                            placeholder="Phone Number">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div> --}}

                        <div class="form-group">
                            <label for="notes" class="text-black">Order Notes</label>
                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control"
                                placeholder="Write your notes here..." required></textarea>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                            <div class="p-3 p-lg-5 border">

                                <label for="c_code" class="text-black mb-3">Enter your coupon code if you have
                                    one</label>
                                <div class="input-group w-75">
                                    <input type="text" class="form-control" id="c_code" placeholder="Soon"
                                        aria-label="Coupon Code" aria-describedby="button-addon2" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm px-4" type="button" id="button-addon2"
                                            disabled>Apply</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Product</th>
                                        <th>Color</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $cart)
                                        <tr>
                                            <td>{{ $cart->name }}</td>
                                            <td class="text-black font-weight-bold"><strong>{{ $cart->color }}</strong>
                                            </td>
                                            <td>{{ $cart->qty }}</td>
                                            <td>${{ $cart->price*$cart->qty }}.00</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-black font-weight-bold">
                                                <strong>Cart Subtotal</strong>
                                            </td>
                                            <td class="text-black">${{ $carts->sum('total') }}.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-black font-weight-bold">
                                                <strong>Shipping Fee</strong>
                                            </td>
                                            <td class="text-black">Free</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-black font-weight-bold">
                                                <strong>Order Total</strong>
                                            </td>
                                            <td class="text-black font-weight-bold">
                                                <strong>${{ $carts->sum('total') }}.00</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsebank"
                                            role="button" aria-expanded="false" aria-controls="collapsebank">Direct Bank
                                            Transfer</a></h3>

                                    <div class="collapse" id="collapsebank">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please use
                                                your Order ID as the payment reference. Your order won’t be shipped
                                                until
                                                the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsecheque"
                                            role="button" aria-expanded="false" aria-controls="collapsecheque">Cheque
                                            Payment</a></h3>

                                    <div class="collapse" id="collapsecheque">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please use
                                                your Order ID as the payment reference. Your order won’t be shipped
                                                until
                                                the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border p-3 mb-5">
                                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsepaypal"
                                            role="button" aria-expanded="false"
                                            aria-controls="collapsepaypal">Paypal</a>
                                    </h3>

                                    <div class="collapse" id="collapsepaypal">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please use
                                                your Order ID as the payment reference. Your order won’t be shipped
                                                until
                                                the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary btn-lg btn-block">Place Order</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

@endsection
