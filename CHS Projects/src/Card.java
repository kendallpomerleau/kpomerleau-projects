/**
 * Card Class
 */
public class Card
{
    private int number;
    private String suit;
    
    /**
     * Constructor
     * @param num The number of the card
     * @param s The suit of the card
     */
    public Card(int num, String s)
    {
        number = num;
        suit = s;
    }

    /**
     * Gets the number on the card
     * @return number
     */
    public int getNumber()
    {
        return number;
    }
    
    /**
     * Gets the suit of the card
     * @return suit
     */
    public String getSuit()
    {
        return suit;
    }
    
    public int findValue()
    {
        if (number == 12 || number == 13 || number == 14)
            return 10;
        else
            return number;
    }
    
    /**
     * Switches a card to be the face card if needed
     * @return J, Q, K, A, or number
     */
    public String switchToFace()
    {
        if (getNumber() == 11)
            return "A";
        else if (getNumber() == 12)
            return "J";
        else if (getNumber() == 13)
            return "Q";
        else if (getNumber() == 14)
            return "K";
        else
            return Integer.toString(getNumber());
    }
    
    public String toString()
    {
        return switchToFace() + " of " + suit + "s";
    }
}
