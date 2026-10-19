<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');   // browser shows plain text, so \n works
ini_set('display_errors', '1');                       // show errors instead of a blank page
ini_set('html_errors', '0');
error_reporting(E_ALL);





abstract class Account{
    // public string $owner;
    // public float $balance;
    // public string $number;
    
    const CURRENCY = "JOD";
    private const MIN_BALANCE = 0.0;

    public function __construct(
        private string $owner,
        protected float $balance,
        private string $number) {

        $this->owner = $owner;
        $this->balance = $balance;
        $this->number = $number;
    }

    //* GETTERS DOWN HERE
    public function getOwner(): string { return $this->owner; }
    public function getBalance():float { return $this->balance; }
    public function getNumber(): string { return $this->number; }
    
    public function deposit(float $amount):void{
        if($amount <= 0){
            throw new InvalidArgumentException("Amount Must Be Positive");
        }
        else{
             $this->balance += $amount;
        }        
    }
    abstract public function monthlyUpdate(): void;

    public function withdraw(float $amount):void{
        if ($amount <= 0) {
        throw new InvalidArgumentException("Amount must be positive");
    }
    if ($this->balance - $amount < self::MIN_BALANCE){
        throw new RuntimeException("Insufficient Funds");
    }
    else{
    $this->balance -= $amount;
    }
    }

    public function getSummary():string{
        return $this->owner . "(". $this->number ."): " . $this->balance . " " . self::CURRENCY; 
    }

    final public function describe():string{
        return get_class($this). ": " . $this->getSummary();
    }




    public function __destruct(){
        echo"\n Closing account " . $this->number . "\n";
    }
}

class SavingsAccount extends Account{
    public function __construct(
        private float $interestRate,
        string $owner,
        float $balance,
        string $number
    )
    {
        
    parent::__construct($owner, $balance, $number);
    
    }
    function monthlyUpdate(): void{
        $this->balance += $this->balance * $this->interestRate / 12;
    }

    function getSummary():string{
         return parent::getSummary() . " @ " . $this->interestRate*100 . "%";
    }
}

class CheckingAccount extends Account{
    private const MONTHLY_FEE = 2.5;
    public function monthlyUpdate(): void{
    $this->balance -= max(0.0 ,$this->balance - self::MONTHLY_FEE);
    }
}

$sara = new SavingsAccount(0.03, "Sara", 100.0, "ACC-1");
echo $sara->getSummary() . "\n";



//!CHECKING TEST
// $sara = new CheckingAccount("Sara", 100.0, "ACC-1");
// echo $sara->getSummary() . "\n";
// $sara->monthlyUpdate();
// echo $sara->getSummary() . "\n";

// $low = new CheckingAccount("Lina", 1.0, "ACC-3");
// $low->monthlyUpdate();
// echo $low->getSummary() . "\n";











// $sara = new Account("Sara", 100.00, "ACC-1");
// $omar = new Account("Omar", 50.00, "ACC-2");

// try {
//     $sara->deposit(-10);
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage() . "\n";
// }

// try {
//     $sara->withdraw(0);
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage() . "\n";
// }

// try {
//     $sara->withdraw(999999);
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage() . "\n";
// }

// $sara->withdraw(0.00);
// echo $sara->getSummary();

// echo $sara->getSummary() . "\n";
// unset($omar);
// echo "after unset\n";

// $sara->owner = "Asem";
// $sara->balance = 25.000;
// $sara->number = "ACC-1";

// echo $sara->owner ." | " . $sara->balance ." | " . $sara->number . "\n";
// echo $omar->owner ." | " . $omar->balance ." | " . $omar->number;