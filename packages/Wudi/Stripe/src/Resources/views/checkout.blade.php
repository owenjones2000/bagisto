<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Accept a payment</title>
  <meta name="description" content="a payment on Stripe" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://js.stripe.com/v3/"></script>
  {{-- <script src="checkout.js" defer></script> --}}
</head>
    <style rel="stylesheet">
        /* spinner/processing state, errors */
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }

        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }

        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: #5469d4;
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }

        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: #5469d4;
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }

        @-webkit-keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        input {
            outline: 0;
        }

        .history-cards {
            width: 100%;
            height: auto;
        }

        .history-card {
            display: flex;
            margin: .5rem 0;
            height: 35px;
            line-height: 35px;
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #CCC;
            font-size: 14px;
        }

        .history-card-checked{
            border-color: #635BFF;
        }

        .history-card-num {
            width: 70%;
            font-weight: bold;
        }

        .history-card-exp {
            font-weight: bold;
            width: 20%
        }

        .payment-form-box {
            max-width: 500px;
            margin: auto;
            padding: .5rem
        }

        .payment-method-head {
            display: flex;
            line-height: 20px;
        }

        .payment-method-img {
            width: auto;
            height: 20px;
            vertical-align: middle;
            padding-right: .2rem;
        }

        .payment-method-img > img {
            width: auto;
            height: 20px;
        }

        .payment-method-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 22px;
            font-family: HelveticaNeue-Bold, HelveticaNeue;
            vertical-align: middle;
        }

        .content-panel {
            margin-top: 1rem;
            box-sizing: border-box;
        }

        #card-num-panel {
            height: 40px
        }

        #card-num {
            width: 100%;
            height: 40px;
            padding-left: .5rem;
            border: solid 1px #635BFF;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            box-sizing: border-box;
        }

        #card-exp {
            float: left;
            box-sizing: border-box;
            width: 50%;
            height: 40px;
            padding-left: .5rem;
            border-left: solid 1px #635BFF;
            border-right: solid 1px #635BFF;
            border-bottom: solid 1px #635BFF;
            border-bottom-left-radius: 5px;
        }

        #card-cvc {
            float: left;
            box-sizing: border-box;
            width: 50%;
            height: 40px;
            padding-left: .5rem;
            border-right: solid 1px #635BFF;
            border-bottom: solid 1px #635BFF;
            border-bottom-right-radius: 5px;
        }

        button#submit {
            background-color: #635bff;
            text-align: center;
            height: 50px;
            color: #fff;
            border-radius: 10px;
            line-height: 50px;
            font-weight: 700;
            width: 100%;
            margin-top: 1rem;
            border: none;
        }

        #payment-message {
            margin: 1rem 0;
            color: #fa755a;
        }

        .hidden {
            display: none;
        }

        .divider {
            display: flex;
            justify-content: center;
            width: 100%;
            height: 1rem;
            margin-bottom: 1rem;
        }

        .divider-hr-box {
            width: 47%;
            padding-top: 2px;
            vertical-align: middle;
        }

        .divider-hr-box > hr {
            box-sizing: border-box;
            border: none;
            border-top: solid 1px #EEE;
            vertical-align: middle;
        }

        .divider-text {
            font-size: 12px;
            color: #999;
            vertical-align: middle;
        }

        .apple-pay {
            width: 100%;
            height: auto;
            margin-top: 1rem;
            display: none;
        }

        .apple-pay-btn {
            margin-top: 1rem;
            background-color: #000000;
            text-align: center;
            height: 50px;
            color: #fff;
            border-radius: 10px;
            line-height: 50px;
            font-weight: 700;
        }

        .tips-text {
            margin: 1rem 0;
            color: #ccc;
            font-size: 12px;
            border-top: solid 1px #EEE;
        }

        .powered-text {
            font-size: 16px;
            text-align: center;
            color: #ccc
        }
    </style>

<body>
  <!-- Display a payment form -->
 <div class="home">
        <div class="payment-form-box">
            <form id="payment-form">
                <input type="hidden" id="_token" value="{{csrf_token()}}">
                <div id="payment-element">
                    <div class="payment-method-head">
                        <div class="payment-method-img">
                            <img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAAAXNSR0IArs4c6QAAA2VJREFUeF7tms1rE2EQh2d2EypJ6get1j/AbpQqWBHFL2yqlcZDPOUkCrVQb3q2CNZCPehJb0opFaSCPXgoFHtoEqnQ4lftoZBuTooUIUawyW7FJjsS9SJ0d/vuWs1uJ9edycw8+c2875BF4I8lAWQ+1gQYkI1CGBADcjdEWEGsIFaQOwKsIHf8eAaxgv6BgpLJpPxsrpAwkNqAoImIZHdh/483IhIRFVCSXoYCDaP5+dGSXSa2LVavnNldgZVhIDps92Weeo74XkK8VFpIpazytgS0eU9Hc7lSngWisKeKX3uyJMvSuWI2NWbmYgqor69Puj3yfAqIjq49nvcsEeGTHKxrWZqf+LJa9qaAQkp7HMgY917JjjK+rucyA0KAwkrsFhFdcxTOY04IOKHl0p2igAaJqNtjtTpKFwFmtVzmgBig5tgQAXU5iugxJ0Sc09T0fgZk8sMxILt1ghVkTYgVtC4Kip7qBMPY57F56zBdzGu51LDQkHYYyXdutsuq7yoWLIgB2c0nQaAbzpwVxApyJ3pWkFMF7YrH675+iATd8feGd3DTN2PxzZgudA8K8zb/k5dpizGgX3piQFUIvM3zNu/qNGAF2R3l3GLcYtxirghwi7nDx0OaFcQKckdgoygIAfIE6PTv8B4ASqy6sfvlHoQIjzQ1c8GJnMLNbW8JoNXfgGQ8r2XTI6KAwi1nd9J3fdFsOffJKYaGLEs7itnJgjCgaHsXGcaQmZ9PAMGMnsscEYVTtQ8rsSdElPQ3IMQbupruFwVUfYV5/F3+MwFs9TWgAMqHltTJV6KA6pXTxytUnrLy83yLVY/3kppuqr4ILgooosQGDKJefwNCTMkElkWaAagADRLAXl8DElWNqL2zFovG7pJBV0SDedIe4YWuZk6seok0KyikxHqA6L4nCxZNGvGBrqYvCwGKtMa3U2lZtToeRfOoUXuSZflYMTs5LQTo9wXrIhE9rNHC/kpaKOE9bSF91fSOZBclosRuGkC9QBCws/Xac0R8vK2hsfvj9OiyY0BVxy3RjoNlWuknwpMAFPIaiD/yRVgBgtcySneKauqpXS3Cr780RhP1lYgu2X1xLT7HQJAKM+NFkQunMKBaLHw9c2JANnQZEANy14CsIFYQK8gdAVaQO348g1hB7hT0AyXgoViH0Ri+AAAAAElFTkSuQmCC"
                                alt=""/>
                        </div>
                        <div class="payment-method-title"> Credit card</div>
                    </div>
                    <!-- 历史支付卡片记录 -->
                    <!--
                    <div class="history-cards hidden">
                        <div class="history-card history-card-checked">
                            <div class="history-card-num">1234 5678 9012 3456</div>
                            <div class="history-card-exp">02/22</div>
                        </div>
                        <div class="history-card">
                            <div class="history-card-num">1234 5678 9012 3456</div>
                            <div class="history-card-exp">02/22</div>
                        </div>
                        <div class="history-card">
                            <div class="history-card-num">1234 5678 9012 3456</div>
                            <div class="history-card-exp">02/22</div>
                        </div>
                    </div>
                    -->

                    <div class="new-card">
                        <div class="content-panel">
                            <div id="card-num"></div>
                            <div id="card-exp"></div>
                            <div id="card-cvc"></div>
                        </div>
                    </div>
                </div>
                <button id="submit" type="button" onclick="handleSubmit();return false;">
                    <div class="spinner hidden" id="spinner"></div>
                    <span id="button-text"> Pay with credit card</span>
                </button>
                <div id="payment-message" class="hidden"></div>
            </form>

            <div class="apple-pay" id="apple-pay">
                <div class="divider">
                    <div class="divider-hr-box">
                        <hr/>
                    </div>
                    <div class="divider-text">or</div>
                    <div class="divider-hr-box">
                        <hr/>
                    </div>
                </div>

                <div class="payment-method-head">
                    <div class="payment-method-img">
                        <img
                            src="data:image/png;base64,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"
                            alt=""/>
                    </div>
                    <div class="payment-method-title">Apple pay</div>
                </div>
                <div class="apple-pay-btn">
                    <div id="payment-request-button">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                </div>
            </div>

            <div class="tips-text">
                <p>
                    You confirm that you are at least 18 year of age by submitting the payment information (depending on
                    the adult age in your local law).
                    And you have read, understood, and agreed to abide by the "Terms&Conditions" of this application and
                    other applicable legal agreements.
                    Please note that the merchant's name on your payment statement will be displayed as "**"
                </p>
                <div class="powered-text">
                    Powered by <span style="font-weight: 700"> {{$appname}}</span>
                </div>
            </div>
        </div>
    </div>

</body>
    <script>
        // the stripe obj and cs
        let stripe = null, clientSecret = null;
        //card input fields
        let cardNum = null, cardExp = null, ardCvc = null;
        // card empty check
        let has_num = false, has_exp = false, has_cvc = false;

        initialize();
        checkStatus();

        async function initialize() {
            // begin loading
            setLoading(true);

            stripe = Stripe("{{$stripe_pb_key}}", {
                apiVersion: "2020-08-27"
            });

            // The items the customer wants to buy
            const items = [{id: "xl-tshirt"}];

            // csrf token
            let csrf_token = document.getElementById('_token').value;
            // get client secret
            let cs = await fetch("{{route('stripe.create')}}", {
                method: "POST",
                headers: {"Content-Type": "application/json", "X-CSRF-Token": csrf_token},
                body: JSON.stringify({items}),
            }).then((r) => r.json());
            clientSecret = cs.clientSecret;
            cs = null;

            /* let elements; */
            let elements = stripe.elements();
            let style = {
                theme: 'stripe',
                base: {
                    color: "#32325d",
                    lineHeight: "40px",
                    fontFamily: "Gilroy-bold",
                    fontSmoothing: "antialiased",
                    fontSize: "14px",
                    "::placeholder": {
                        color: "#aab7c4",
                    },
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a",
                },
            };

            // create elements
            cardNum = elements.create("cardNumber", {style: style});
            cardExp = elements.create("cardExpiry", {style: style});
            cardCvc = elements.create("cardCvc", {style: style});

            // mount elements to dom
            cardNum.mount("#card-num");
            cardExp.mount("#card-exp");
            cardCvc.mount("#card-cvc");

            // add event listener
            cardNum.on('change', function (event) {
                if (!has_num) {
                    has_num = true;
                }
                if (event.error) {
                    showMessage(event.error.message);
                }
            });
            cardExp.on('change', function (event) {
                if (!has_exp) {
                    has_exp = true;
                }
                if (event.error) {
                    showMessage(event.error.message);
                }
            });
            cardCvc.on('change', function (event) {
                if (!has_cvc) {
                    has_cvc = true;
                }
                if (event.error) {
                    showMessage(event.error.message);
                }
            });

            //apple pay
            let paymentRequest = stripe.paymentRequest({
                country: 'US',
                currency: 'usd',
                total: {
                    label: 'total',
                    amount: {{$total_amount}},
                },
                requestPayerName: true,
                requestPayerEmail: true,
            });

            // apple pay btn
            let prButton = elements.create('paymentRequestButton', {
                paymentRequest: paymentRequest,
            });

            // Check the availability of the Payment Request API first.
            paymentRequest.canMakePayment().then(function (result) {
                if (result) {
                    prButton.mount('#payment-request-button');
                    document.getElementById('apple-pay').style.display = 'block';
                } else {
                    document.getElementById('apple-pay').style.display = 'none';
                }
            });

            // do apple-pay payment
            paymentRequest.on('paymentmethod', function (ev) {
                // Confirm the PaymentIntent without handling potential next actions (yet).
                stripe.confirmCardPayment(
                    clientSecret,
                    {payment_method: ev.paymentMethod.id},
                    {handleActions: false}
                ).then(function (confirmResult) {
                    if (confirmResult.error) {
                        // Report to the browser that the payment failed, prompting it to
                        // re-show the payment interface, or show an error message and close
                        // the payment interface.
                        ev.complete('fail');
                    } else {
                        // Report to the browser that the confirmation was successful, prompting
                        // it to close the browser payment method collection interface.
                        ev.complete('success');
                        // Check if the PaymentIntent requires any actions and if so let Stripe.js
                        // handle the flow. If using an API version older than "2019-02-11"
                        // instead check for: `paymentIntent.status === "requires_source_action"`.
                        if (confirmResult.paymentIntent.status === "requires_action") {
                            // Let Stripe.js handle the rest of the payment flow.
                            stripe.confirmCardPayment(clientSecret).then(function (result) {
                                if (result.error) {
                                    // The payment failed -- ask your customer for a new payment method.
                                    showMessage(result.error.message);
                                } else {
                                    // The payment has succeeded.
                                    showMessage('pay success!', "{{route('stripe.success')}}");
                                }
                            });
                        } else {
                            // The payment has succeeded.
                            showMessage('pay success!', "{{route('stripe.success')}}");
                        }
                    }
                });
            });

            // end loading
            setLoading(false);
        }

        // do card payment
        function handleSubmit() {
            if (!has_num) {
                showMessage('please input the card number!');
                return false;
            }
            if (!has_exp) {
                showMessage('please input the card expire info!');
                return false;
            }
            if (!has_cvc) {
                showMessage('please input the card cvc code!');
                return false;
            }
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {card: cardNum},
                setup_future_usage: 'off_session'
            }).then(function (res) {
                if (res.error) {
                    console.log(res);
                    showMessage(res.error.message);
                } else {
                    if (res.paymentIntent.status === 'succeeded') {
                        // Show a success message to your customer
                        // There's a risk of the customer closing the window before callback execution
                        // Set up a webhook or plugin to listen for the payment_intent.succeeded event
                        // to save the card to a Customer
                        // location.href="/p/s"
                        // The PaymentMethod ID can be found on result.paymentIntent.payment_method
                        showMessage('pay success!', "{{route('stripe.success')}}");
                    }
                }
            });
        }

        // Fetches the payment intent status after payment submission
        async function checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const {paymentIntent} = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    showMessage("Payment succeeded!");
                    break;
                case "processing":
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    showMessage("Something went wrong.");
                    break;
            }
        }

        // ------- UI helpers -------

        function showMessage(messageText, url) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
                if (url) {
                    location.href = url;
                }
            }, 3000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>

</html>