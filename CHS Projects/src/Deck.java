import java.util.Random;

/**
 * Deck Class
 */
public class Deck
{
    private Card[] deck;
    private int numCardOn;
    private Card drawn;
    
    /**
     * Constructor
     * @param n The suit of the deck
     */
    public Deck()
    {
        deck = new Card[52];
        numCardOn = 0;
        drawn = null;

        String suitAsString = "";
        int count = 0;
        
        for (int j = 0; j < 4; j++)
        {
            if (j == 0)
                suitAsString = "Heart";
            else if (j == 1)
                suitAsString = "Spade";
            else if (j == 2)
                suitAsString = "Diamond";
            else if (j == 3)
                suitAsString = "Club";
            
            for (int i = 2; i <= 14; i++)
            {
                deck[count] = new Card(i, suitAsString);
                count++;
            }
        }     
        
        shuffle();


    }
    /**
     * Draws a card
     * @return the next card in the deck
     */
    public void drawCard()
    {
        drawn = deck[numCardOn];
        numCardOn++;                
    }
    
    /**
     * Shuffles the deck by switching two of the cards
     */
    public void shuffle()
    {
        Random r = new Random();
        
        for (int i = 0; i < 150; i++)
        {
            int rand1 = r.nextInt(51 - 0 + 1) + 0;
            int rand2 = r.nextInt(51 - 0 + 1) + 0;
            
            if (rand1 != rand2)
            {
                Card temp = new Card(deck[rand2].getNumber(), deck[rand2].getSuit());
                deck[rand2] = null;
                deck[rand2] = new Card(deck[rand1].getNumber(), deck[rand1].getSuit());
                deck[rand1] = null;
                deck[rand1] = new Card(temp.getNumber(), temp.getSuit());
            }

        }       
    }
    
    /**
     * Return the deck as a string
     */
    public String toString()
    {
        String toReturn = "";
        for (int i = 0; i < 52; i++)
        {
            toReturn += deck[i].getNumber() + " " + deck[i].getSuit() + "\n";
        }
        return toReturn;
    }
    /**
     * Gets the drawn card
     * @return drawn
     */
    public Card getDrawn()
    {
        return drawn;
    }
}
