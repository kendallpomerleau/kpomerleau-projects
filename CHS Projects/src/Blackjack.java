import java.util.Scanner;

/**
 * Blackjack
 * Gets bet, deals hands, and determines winner of blackjack game
 * @author kendallpomerleau Kendall Pomerleau
 * Java Period 2
 * @version 1.0
 */
public class Blackjack
{
    public static void main(String[] args)
    {
        //get user input
        String name = getUserName(); //get name for new user
        User u = new User(name); //declare new user
        while (u.getMoney() > 0) //while the user has money to bet
        {
            Dealer d = new Dealer(); //reset dealer
            u.reset(); //reset user
            Deck playDeck = new Deck(); //get new deck
            playDeck.shuffle(); //shuffle the deck
            boolean hit = true;
            Scanner input = new Scanner(System.in);
            
            System.out.println(name + " has $" + u.getMoney());
            boolean toLoop = true; //loop for getting first bet
            while (toLoop == true)
            {
                try
                {
                    u.setBet(getUserBet(u.getBet())); //get the user's bet
                    if (u.getBet() > u.getMoney()) //if the bet is more than money user has
                    {
                        System.out.println("Enter a valid bet.");
                        u.setBet(25);
                        toLoop = true;
                    }
                    else //if the bet is valid
                        toLoop = false;
                }catch (java.lang.NumberFormatException ex) //if the bet is not valid
                {
                    System.out.println("Enter a valid bet.");
                    toLoop = true;
                }
            }
            
            if (u.getBet() != 0) //if user did not quit
            {
                //dealer's card
                d.dealerTurn(playDeck); //dealer takes a card
                
                System.out.println("Bet: $" + u.getBet());
                firstDeal(u, d, playDeck); //user takes two cards
                hit = getHitStay(); //find if the user wants to hit or stay
                
                while (hit == true) //while the user wants to hit
                {            
                    userPlay(u, d, playDeck); //play another card
                    if (u.findTotal() < 21) //if the user didn't win or bust
                        hit = getHitStay(); //prompt to play another card
                    else
                        hit = false;        
                }
                
                if (u.findTotal() == 21) //if user got blackjack
                {
                    System.out.println("Congratulations! You won Blackjack!");
                    System.out.println("");
                    u.setMoney(u.getMoney() + u.getBet());
                }
                else if (u.findTotal() > 21) //if user busted
                {
                    System.out.println(u.getName() + " bust.");
                    System.out.println("");
                    u.setMoney(u.getMoney() - u.getBet());
                }
                else //if user just stayed
                {

                     while (d.findTotal() < 17) //if dealer has less than 17 points
                          dealerPlay(u, d, playDeck); //deal another card
                     determineWinner(u, d, playDeck, u.getBet()); //find the winner
                }
            }
            else //if user quit
                u.setMoney(0); 
        }
    }

    /**
     * Finds who the winner is and does corresponding actions
     * @param us User
     * @param de Dealer
     * @param dec Deck
     * @param bet Bet the user made
     */
    private static void determineWinner(User us, Dealer de, Deck dec, int bet)
    {
        
        if (de.findTotal() <= 21 && de.findTotal() > us.findTotal())
        {
            System.out.println("Dealer wins.");
            us.setMoney(us.getMoney() - bet);
            System.out.println("");
        }
        else if ((de.findTotal() <= 21 && de.findTotal() < us.findTotal()) || de.findTotal() > 21)
        {
            System.out.println("You win.");
            us.setMoney(us.getMoney() + bet);
            System.out.println("");           
        }
        else if (de.findTotal() == us.findTotal())
        {
            System.out.println("It's a push. You tied with the dealer.");            
            System.out.println("");
        }
        
    }
    
    /**
     * Shows the hit for the dealer
     * @param us User
     * @param de Dealer
     * @param dec Deck
     */
    private static void dealerPlay(User us, Dealer de, Deck dec)
    {
        //user's cards
        de.hit(dec);
        
        //output
        System.out.println("Dealer's Hand: " + de); //output user's hand
        System.out.println("Value: " + de.findTotal()); //output value of user's hand
                   
        //output
        System.out.println(us.getName() + "'s Hand: " + us); //output user's hand
        System.out.println("Value: " + us.findTotal()); //output value of user's hand
        System.out.println("");
        
    }

    /**
     * Shows the hit for a user
     * @param us User
     * @param deal Dealer
     * @param de Deck
     */
    private static void userPlay(User us, Dealer deal, Deck de)
    {  
        //output
        System.out.println("Dealer's Hand: " + deal); //output user's hand
        System.out.println("Value: " + deal.findTotal()); //output value of user's hand
        
        //user's cards
        us.hit(de);
                   
        //output
        System.out.println(us.getName() + "'s Hand: " + us); //output user's hand
        System.out.println("Value: " + us.findTotal()); //output value of user's hand
        System.out.println("");
    }
    /**
     * Finds whether or not the user chose to hit
     * @return boolean hit or stay (hit = true, stay = false)
     */
    private static boolean getHitStay()
    {
        System.out.println("Move? (hit[h]/stay[s])");
        Scanner in = new Scanner(System.in);
        
        String nextPlay = in.nextLine();
        
        boolean hit = true;
        boolean loop = true;
        
        //HIT OR STAY
        while (loop == true)
        {
            if (nextPlay.equals("h"))
            {
                hit = true;
                loop = false;
            }               
            else if (nextPlay.equals("s"))
            {
                hit = false;
                loop = false;
            }
            else
            {
                loop = true;
                System.out.println("Enter either h or s for hit or stay.");
                nextPlay = in.nextLine();
            }   
        }
        return hit; 
    }
    /**
     * First deal for the user
     * @param us User
     * @param deal Dealer
     * @param de Deck
     */
    private static void firstDeal(User us, Dealer deal, Deck de)
    {
        //output
        System.out.println("Dealer's Hand: " + deal); //output user's hand
        System.out.println("Value: " + deal.findTotal()); //output value of user's hand
        
        //user's cards
        us.hit(de);
        us.hit(de);
                   
        //output
        System.out.println(us.getName() + "'s Hand: " + us); //output user's hand
        System.out.println("Value: " + us.findTotal()); //output value of user's hand
        System.out.println("");
    }
    /**
     * Gets the user's inputed bet
     * @param b bet the user already made
     * @return new bet
     */
    private static int getUserBet(int b)
    {      
        Scanner in = new Scanner(System.in);
        System.out.println("Bet: (0 to quit, Enter to stay at $" + b + ")");   
        String readString = in.nextLine();
        if (readString.equals(""))
            return b;
        else
            return Integer.parseInt(readString);
    }   
    
    /**
     * Gets the user's inputed name
     * @return the name
     */
    private static String getUserName()
    {
        Scanner in = new Scanner(System.in);
        System.out.print("Name: ");
        return in.nextLine();
    }
}
