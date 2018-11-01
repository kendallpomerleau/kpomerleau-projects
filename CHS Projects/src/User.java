import java.util.Scanner;

/**
 * User Class
 */
public class User
{
    private String name;
    private Card[] hand = new Card[50];
    private int uCount;
    private int money;
    private int bet;
    
    /**
     * Constructor
     * @param n name
     */
    public User(String n)
    {
        name = n;
        uCount = 0;
        money = 1000;
        bet = 25;
        for (int i = 0; i < hand.length; i++)
        {
            hand[i] = null;
        }
    }
    /**
     * Gets the bet the user made
     * @return bet
     */
    public int getBet()
    {
            return bet;
    }
    /**
     * Gets the total money the user has
     * @return amount of money
     */
    public int getMoney()
    {
        return money;
    }
    /**
     * Gets the name of the user
     * @return name
     */
    public String getName()
    {
        return name;
    }
    
    /**
     * Gets the count that the user is on for their hand
     * @return uCount
     */
    public int getCount()
    {
        return uCount;
    }

    
    /**
     * Sets the total money the user has
     * @param amt amount that the user will have
     */
    public void setMoney(int amt)
    {
        money = amt;
    }
    /**
     * Sets the bet the user makes
     * @param b the new bet amount
     */
    public void setBet(int b)
    {
        bet = b;
    }
    /**
     * Gets the user's hand
     * @return hand
     */
    public Card[] getuHand()
    {
        return hand;
    }
    
    /**
     * Add to user's counter
     */
    public void uIncrement()
    {
        uCount++;
    }
    
    /**
     * Finds the value of the user's hand
     * @return total value
     */
    public int findTotal()
    {
        int total = 0;
        for (int i = 0; i < hand.length; i++)
        {
            if (hand[i] != null)
            {
                if (hand[i].findValue() == 11)
                {
                    if (total + 11 > 21)
                        total += 1;
                    else
                        total += 11;
                }
                else
                    total += hand[i].findValue();
            }
                
        }
        return total;
    }
    
    /**
     * Gets the user's hand as a string
     */
    public String toString()
    {
        String toReturn = "";
        for (int i = 0; i < hand.length; i++)
        {
            if (hand[i] != null)
            {
                hand[i].switchToFace();
                toReturn += (hand[i]) + " ";
            }     
        }
        return toReturn;
    }

    /**
     * Turn for the user (draws two cards and adds to array)
     * @param dec the deck to draw from
     */
    public void hit(Deck dec)
    {
        dec.drawCard();
        Card first = dec.getDrawn();
        getuHand()[getCount()] = first;
        uIncrement();
    }
    
    /**
     * Resets the user's hand to nothing
     */
    public void reset()
    {
        for (int i = 0; i < hand.length; i++)
        {
            hand[i] = null;
        }
    }
}