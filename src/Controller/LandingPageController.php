<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\DeliveryAdress;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\DeliveryAdressRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Api;
use DateTime;
use Doctrine\ORM\Query\Expr\Func;
use PhpParser\Builder\Function_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LandingPageController extends AbstractController
{

    private $apiclient;

    public function __construct(HttpClientInterface $apiclient)
    {
        $this->apiclient = $apiclient;
    }



    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */
    public function index(Request $request, ClientRepository $client, ProductRepository $productRepository)
    {


        $client = new Client();

        $order = new Order();

        $adress = new DeliveryAdress();


        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $productId = $request->get('product');
            $product = $productRepository->findOneBy(['id' => $productId]);

            if (!isset($productId)) {
                $this->addFlash(
                    'notice',
                    'Please Select a product'
                );
                return $this->redirectToRoute('landing_page');
            }
            $client->setcreatedAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $adress = $client->getDeliveryAdress();
            $order->setIdDeliveryAdress($adress);
            $order->setIdClient($client);
            $order->setIdProduct($product);
            $order->setPayementMethod($request->get('paymentMethod'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);

            // on set le status waiting dans la bdd

            $order->setStatus('WAITING');

            $paymentMethod = $request->get('paymentMethod'); // RECUP NAME PAYMENTMETHOD DANS FORM

            // on appelle l'api
            $this->api($order);

            $entityManager->flush();


            $product = $productRepository->findAll();

            // IF POUR BTN PAIEMENT STRIPE/PAYPAL
            if ($paymentMethod === 'stripe') {

                return $this->redirectToRoute('stripe', [
                    'id' => $order->getId()
                ]);
            } else {
                return $this->redirectToRoute('paypal', [
                    'id' => $order->getId()
                ]);
            }
            return $this->redirectToRoute('landing_page');
        }
        $product = $productRepository->findAll();
        return $this->render('landing_page/index_new.html.twig', [
            'client' => $client,
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }








    // function API

    public function api(Order $order)
    {
        $token = 'mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX';


        if($order->getIdDeliveryAdress()->getAdress() == null){
            $datas =
            [
                "order" => [
                    "id" => $order->getId(),
                    "product" => $order->getIdProduct()->getName(),
                    "payment_method" => "paypal",
                    "status" => "WAITING",
                    "client" => [
                        "firstname" => $order->getIdClient()->getName(),
                        "lastname" => $order->getIdClient()->getLastname(),
                        "email" => $order->getIdClient()->getEmail(),
                    ],
                    "addresses" => [
                        "billing" => [
                            "address_line1" => $order->getIdClient()->getAdress(),
                            "address_line2" => $order->getIdClient()->getCompAdress(),
                            "city" => $order->getIdClient()->getCity(),
                            "zipcode" => $order->getIdClient()->getCodePostal(),
                            "country" => $order->getIdClient()->getPays(),
                            "phone" => $order->getIdClient()->getPhone(),
                        ],
                    "shipping" => [
                        "address_line1" => $order->getIdClient()->getAdress(),
                        "address_line2" => $order->getIdClient()->getCompAdress(),
                        "city" => $order->getIdClient()->getCity(),
                        "zipcode" => $order->getIdClient()->getCodePostal(),
                        "country" => $order->getIdClient()->getPays(),
                        "phone" => $order->getIdClient()->getPhone(),
                    ],
                 ],
                ]
            ];
        }else{
            $datas =
            [
                "order" => [
                    "id" => $order->getId(),
                    "product" => $order->getIdProduct()->getName(),
                    "payment_method" => $order->getPayementMethod(),
                    "status" => "WAITING",
                    "client" => [
                        "firstname" => $order->getIdClient()->getName(),
                        "lastname" => $order->getIdClient()->getLastname(),
                        "email" => $order->getIdClient()->getEmail(),
                    ],
                    "addresses" => [
                        "billing" => [
                            "address_line1" => $order->getIdClient()->getAdress(),
                            "address_line2" => $order->getIdClient()->getCompAdress(),
                            "city" => $order->getIdClient()->getCity(),
                            "zipcode" => $order->getIdClient()->getCodePostal(),
                            "country" => $order->getIdClient()->getPays(),
                            "phone" => $order->getIdClient()->getPhone(),
                        ],
                        "shipping" => [
                            "address_line1" => $order->getIdDeliveryAdress()->getAdress(),
                        "address_line2" => $order->getIdDeliveryAdress()->getCompAdress(),
                        "city" => $order->getIdDeliveryAdress()->getCity(),
                        "zipcode" => $order->getIdDeliveryAdress()->getCodePostal(),
                        "country" => $order->getIdDeliveryAdress()->getPays(),
                        "phone" => $order->getIdDeliveryAdress()->getPhone(),
                        ],
                    ]
                ]
            ]; 
        }

        $client = HttpClient::create();
        $response = $client->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-type' => 'application/json',
            ],

            'body' => json_encode($datas),

        ]);


        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json' 

        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'

        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        $entityManager = $this->getDoctrine()->getManager();
        $order->setOrderId($content['order_id']);
        return $content['order_id'];
    }



    public function apiPaid(Order $order)
    {

        $idorder = $order->getOrderId();
        $token = 'mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX';

        $data = [
            "status" => "PAID",
        ];


        $client = HttpClient::create();
        $response = $client->request('POST', 'https://api-commerce.simplon-roanne.com/order/' . $idorder . '/status', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-type' => 'application/json',
            ],

            'body' => json_encode($data),

        ]);


        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json' 

        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'

        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }


    // fin fonction API



    // fonciton payement


    /**
     * @Route("/stripe/{id}", name="stripe", methods={"GET"})
     */
    public function stripe(Request $request, Order $order, MailerInterface $mailer) // Produit $produit
    {
        $this->payment($order);

        if (isset($_GET["click"])) {

            $this->apiPaid($order);

            $entityManager = $this->getDoctrine()->getManager();
            $order->setStatus('PAID');
            $entityManager->persist($order);
            $entityManager->flush();
            $this->sendEmail($mailer, $order);
            return $this->redirectToRoute('confirmation');
            
        }

        return $this->render('landing_page/partials/stripe.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/paypal/{id}", name="paypal", methods={"GET"})
     */
    public function paypal(Request $request, Order $order,MailerInterface $mailer)
    {
    $this->payment($order);

    if (isset($_GET["click"])) {

        $this->apiPaid($order);

        $entityManager = $this->getDoctrine()->getManager();
        $order->setStatus('PAID');
        $entityManager->persist($order);
        $entityManager->flush();
        $this->sendEmail($mailer, $order);
        return $this->redirectToRoute('confirmation');
        
    }
    
    {
        return $this->render('landing_page/partials/paypal.html.twig', [
            'order' => $order
        ]);
    }
}

    public function payment($order)
    {
        // INSTANCIER STRIPE + PUBLIC KEY
        \Stripe\Stripe::setApiKey('sk_test_51J82eiBnNdMIPf3KX14Cq4OqAT9yx2GJ31jO0QCOGS0qZIp1qMzhp9VSeRZJXTs2XAtHWvaZilLZiFnnRitFBgh300PcQUob5X');
        //CREER INTENTION PAIEMENT ET STOCK ANSWER VAR $paymentIntent
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $order->getIdProduct()->getPromoPrice(), // PRIX EN CENTIMES
            'currency' => 'eur',
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];
    }

    // fin fonction payement 


    // fonction mail


    public function sendEmail(MailerInterface $mailer, $order)
    {
        $email = (new Email())
            ->from('nerf-shop@gmail.com')
            ->to($order->getIdClient()->getEmail())
            ->subject('Merci pour votre achat !')
            ->text('Nous vous confirmons que votre commande à bien été prise en compte. Nous vous enverrons un mail dès quil sera expédié. A bientôt sur Nerf Shop');

        $mailer->send($email);

        // ...
    }


    //fin fonction mail

}
