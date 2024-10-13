<div class="container mt-5" wire:init="init">
    <!-- Billing Header -->
    @if ($loading)
        {{-- vertically centered --}}
        <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">
                                {{ __('Loading...') }}
                            </span>
                        </div>
                        <h4 class="text-center ms-3">
                            {{ __('Loading...') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    @else
        @if ($plans)
            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <h2 class="text-center">Billing Page</h2>
                    <!-- Toggles for Switching between Monthly and Yearly Billing -->
                    <div class="text-center">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" wire:model.live="billingToggle" id="monthlyToggle"
                                autocomplete="off" value="monthly">
                            <label class="btn btn-outline-dark" for="monthlyToggle" wire:loading.attr="disabled">
                                Monthly
                            </label>

                            <input type="radio" class="btn-check" wire:model.live="billingToggle" id="yearlyToggle"
                                autocomplete="off" value="yearly">
                            <label class="btn btn-outline-dark" for="yearlyToggle" wire:loading.attr="disabled">
                                Yearly
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pricing Table Section -->
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-header bg-dark text-white">
                                    {{ $plan['name'] }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        @if ($billingToggle === 'monthly')
                                            @money($plan['monthly_price'], $plan['monthly_currency']) / {{ __('month') }}
                                        @else
                                            @money($plan['yearly_price'], $plan['yearly_currency']) / {{ __('year') }}
                                        @endif
                                    </h5>
                                    <p class="card-text">{{ $plan['description'] }}</p>
                                    @if ($plan['features'])
                                        <ul class="list-group">
                                            @foreach ($plan['features'] as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if (!$plan['current'])
                                        <button class="btn btn-dark" wire:click="choosePlan('{{ $plan['id'] }}')"
                                            wire:loading.attr="disabled">
                                            <div class="spinner-border spinner-border-sm" wire:loading
                                                wire:target="choosePlan('{{ $plan['id'] }}')"></div>
                                            Choose Plan
                                        </button>
                                    @else
                                        <button class="btn btn-dark" wire:click="changePlan('{{ $plan['id'] }}')"
                                            wire:loading.attr="disabled">
                                            <div class="spinner-border spinner-border-sm" wire:loading
                                                wire:target="changePlan('{{ $plan['id'] }}')"></div>
                                            Change Plan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Address Setting Section -->
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">{{ __('Billing Address') }}</div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <label for="addressLine1" class="form-label col-md-3 text-end">Address Line
                                    1</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="addressLine1"
                                        placeholder="1234 Main St">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="addressLine2" class="form-label col-md-3 text-end">Address Line
                                    2</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="addressLine2"
                                        placeholder="Apartment, studio, or floor">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="city" class="form-label col-md-3 text-end">City</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="city" placeholder="City">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="zipCode" class="form-label col-md-3 text-end">Zip Code</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="zipCode" placeholder="Zip Code">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mx-auto">
                                    <button type="submit" class="btn btn-dark">Save Address</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Invoice Email Section -->
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">Invoice Email</div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <label for="email" class="form-label col-md-3 text-end">Email</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="email"
                                        placeholder="your@email.com">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mx-auto">
                                    <button type="submit" class="btn btn-dark">Save Address</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Invoices List Section -->
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">Your Invoices</div>
                    <div class="card-body">
                        <table class="table table-light table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2024-10-01</td>
                                    <td>Basic Plan - October</td>
                                    <td>$10</td>
                                    <td>Paid</td>
                                    <td><a href="#" class="btn btn-sm btn-dark">Download</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>2024-09-01</td>
                                    <td>Basic Plan - September</td>
                                    <td>$10</td>
                                    <td>Paid</td>
                                    <td><a href="#" class="btn btn-sm btn-dark">Download</a></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>2024-08-01</td>
                                    <td>Basic Plan - August</td>
                                    <td>$10</td>
                                    <td>Pending</td>
                                    <td><a href="#" class="btn btn-sm btn-dark">Pay Now</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        {{ __('No plans available at the moment.') }}
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
