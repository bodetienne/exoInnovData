<?php

// le namespace des controllers sera toujours le même
namespace App\Controller;

// La classe Response nous sert pour renvoyer la réponse (voir après)
use Symfony\Component\HttpFoundation\Response;
// la classe Controller est la classe mère de tous les controllers
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormClient;
use App\Service\MessageGenerator;

// notre controller doit forcément hériter de la classe Controller ("use" ci-dessus)
// Le nom de la classe doit être exactement le même que celui du fichier
class MainController extends Controller {

  public function index(){
    return $this->render('index.html.twig');
  }

  public function sendMail(\Swift_Mailer $mailer, $id){


    $repo = $this->getDoctrine()->getRepository(Client::class);
    $client = $repo->find($id);
    //var_dump($client);
    $mail_client = $client->mail;
    var_dump($mail_client);

    $message = (new \Swift_Message('Salut '))
      ->setFrom('bodet.etienne79@gmail.com')
      ->setTo($mail_client)
      ->setBody('GRrr', 'text/plain');

      dump($message);

    $mailer->send($message);



    return $this->render('emails.html.twig');
   }

   public function test($id){
     var_dump($id);
   }

  public function form_client(Request $request, \Swift_Mailer $mailer){
    //création d'une nouvelle entité client
    $client = new Client();

    //nous précisons que le formulaire créé un client
    $form = $this->createForm(FormClient::class, $client, [
      'action_name' => 'create' // valeur a envoyer
    ]);


      // nous récupérons ici les informations du formulaire validée
      $form->handleRequest($request);

      // Si nous venons de valider le formulaire et s'il est valide (problèmes de type, etc)
      if ($form->isSubmitted() && $form->isValid()) {
          // nous enregistrons directement l'objet $client !
          $em = $this->getDoctrine()->getManager();
          $em->persist($client);
          $em->flush();

          $id_client = $client->getId();

          // Création de transport
          //465 correspond au code smtp de gmail
          $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587))
            ->setUsername('bodet.etienne79@gmail.com')
            ->setPassword('etibod99917002')
            ;

          // Create the Mailer using your created Transport
          $mailer = new \Swift_Mailer($transport);

          $repo = $this->getDoctrine()->getRepository(Client::class);
          $client = $repo->find($id_client);
          //var_dump($client);
          $mail_client = $client->mail;
          //($mail_client);

          $message = (new \Swift_Message('Salut '))
            ->setFrom('bodet.etienne79@gmail.com')
            ->setTo($mail_client)
            ->setBody('GRrr', 'text/plain');

          //dd($message);

          dd($mailer->send($message));
          //return $this->redirectToRoute('index');
      }
      return $this->render('client/new.html.twig', array(
          'form' => $form->createView(),
      ));

    }


    public function modify(Request $request, $id){

      $repo = $this->getDoctrine()->getRepository(Client::class);
      $client = $repo->find($id);
      //var_dump($client);
      if(!isset($client)){
        return new Response('L\'id entré est nul');
      } else {


        // Nous précisons ici que nous voulons utiliser `FormClient` et hydrater $client

        $form = $this->createForm(FormClient::class, $client, [
          'action_name' => 'modify' // valeur a envoyer
        ]);

        // nous récupérons ici les informations du formulaire validée c'est-à-dire l'équivalent du $_POST
        // ... et ce, grâce à l'objet $request qui représente les informations sur la requête HTTP reçue (voir l'explication après le code)
        $form->handleRequest($request);

        // Si nous venons de valider le formulaire et s'il est valide (problèmes de type, etc)
        if ($form->isSubmitted() && $form->isValid()) {
            // nous enregistrons directement l'objet $client !
            // En effet, il a été hydraté grâce au paramètre donné à la méthode createFormBuilder !
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            // nous redirigeons l'utilisateur vers la route /Client/
            // nous utilisons ici l'identifiant de la route, créé dans le fichier yaml (il est peut-être différent pour vous, adaptez en conséquence)
            // extrèmement pratique : si nous devons changer l'url en elle-même, nous n'avons pas à changer nos contrôleurs, mais juste les fichiers de configurations yaml
            return $this->redirectToRoute('index');
        }

        return $this->render('client/new.html.twig', array(
            'form' => $form->createView(),
        ));
      }

    }

    public function delete($id){
      $client = $this->getDoctrine()->getRepository(Client::class)->find($id);
      if(isset($client)){
        $em = $this->getDoctrine()->getManager();
        $em->remove($client);
        $em->flush();
        sendMail($id);
      } else {
        return new Response('L\'id entré est nul');
      }
    }

    // public function messageGenerator(MessageGenerator $messages){
    //   $service = new MessageGenerator();
    //
    //   return $this->render('message.html.twig', array(
    //     'messages' => $service->getMessage()
    //   ));
    //
    // }




  }
