
/**
 * Dealer class
 */
public class Dealer
{
    private Card[] hand = new Card[50];
    private int dCount;
    
    /**
     * Constructor
     */
    public Dealer()
    {
        dCount = 0;
        for (int i = 0; i < hand.length; i++)
        {
            hand[i] = null;
        } 
    }
    
    /**
     * Gets the count that the dealer is on for their hand
     * @return dCount
     */
    public int getCount()
    {
        return dCount;
    }
    
    /**
     * Gets the dealer's hand
     * @return hand
     */
    public Card[] getdHand()
    {
        return hand;
    }
    
    /**
     * Add to dealer's counter
     */
    public void dIncrement()
    {
        dCount++;
    }

    /**
     * Finds the value of the dealer's hand
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
     * Gets the dealer's hand as a string
     */
    public String toString()
    {
        String toReturn = "";
        for (int i = 0; i < hand.length; i++)
        {
            if (hand[i] != null)
                toReturn += hand[i] + " ";
        }
        return toReturn;
    }

    /**
     * Hit for the dealer 
     * @param dec The deck to draw from
     */
    public void dealerTurn(Deck dec)
    {
        dec.drawCard();
        getdHand()[getCount()] = dec.getDrawn();
        dIncrement();
    }
    
    /**
     * Turn for the dealer (draws two cards and adds to array)
     * @param dec the deck to draw from
     */
    public void hit(Deck dec)
    {
        dec.drawCard();
        Card first = dec.getDrawn();
        getdHand()[getCount()] = first;
        dIncrement();
    }
}
