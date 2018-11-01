
/**
 * Tester for the deck and card classes 
 * @author kendallpomerleau Kendall Pomerleau
 * @version 1.0
 */
public class CHSProject3 //deck tester
{
    public static void main(String[] args)
    {
        //declare new deck
        Deck newDeck = new Deck();

        //shuffle deck
        newDeck.shuffle();
        
        //draw three cards and output them
        newDeck.drawCard();
        System.out.println(newDeck.getDrawn());
        newDeck.drawCard();
        System.out.println(newDeck.getDrawn());
        newDeck.drawCard();
        System.out.println(newDeck.getDrawn());
    }

    
}
