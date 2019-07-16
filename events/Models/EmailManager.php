
<?php
    // use PHPMailer\PHPMailer\PHPMailer;
    // require "vendor/autoload.php";
    require 'PHPMailerAutoload.php';

    class SpectrumEmail extends PHPMailer{
        private const hostname = "smtp.gmail.com";
        private const port = 587;
        private const username = "ubspectrumuser@gmail.com";
        private const from = "ubspectrumuser@gmail.com";
        private const password = "Spectrum2019!";

        public function __construct(){
            parent::__construct(true);

            $this->isSMTP();
            $this->Port = self::port;
            $this->Host = self::hostname;
            $this->SMTPAuth = true;
            $this->Username = self::username;
            $this->Password = self::password;
            $this->SMTPSecure = 'tls';
            $this->WordWrap = 50;     
            $this->isHTML(true);
            $this->setFrom(self::from);
            // $this->SMTPDebug = 2;
            $this->FromName = 'Ub Spectrum';
        }
        
        public function sendMessage($to, $subject, $message, $altMessage = ""){
             $this->Subject = $subject;
             $this->Body    = $message;
             foreach ($to as $address) {
                $this->AddAddress($address);
             }
             $this->AltBody = $altMessage;
           
             if(!$this->send()) {
                $this->ClearAddresses();
                 return false;
             }

             $this->ClearAddresses();
             return true;

        }
    }
?>