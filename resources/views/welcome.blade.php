<x-guest-layout>
    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">

            <div class="w-full sm:max-w-5xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                <div class="px-4 py-2 my-5 text-center">
                    <img class="d-block mx-auto mb-4" src="{{ asset('images/chenyang.png') }}" alt="" width="72" height="57">
                    <h2 class="display-5 fw-bold">
                        HONGKONG CHENYANG
                        <br>
                        TRADING COMPANY LTD
                    </h2>
                    <div class="col-lg-6 mx-auto">
                        <p class="lead mb-4">
                            This web application was designed to extract data from bigsize JSON and XML files.
                        </p>


                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a class="btn btn-primary btn-lg px-4 gap-3 text-white" href="{{ route('login') }}" role="button">Login</a>
                            {{--
                            <a class="btn btn-danger btn-lg px-4 gap-3 text-white" href="{{ route('register') }}" role="button">Register</a>

                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>