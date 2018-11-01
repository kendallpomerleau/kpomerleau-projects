import java.io.File;
import java.io.FileNotFoundException;
import java.util.Arrays;
import java.util.Scanner;

/**
 * Program that takes data from a file of teams and 5 statistics each and <br>
 * Finds the average, max and min of each statistic
 * @author Kendall Pomerleau, Java Period 2
 * @version 1.0
 */
public class CHSProject2
{
    public static void main(String[] args) throws FileNotFoundException
    {
        //declarations;
        int numberOfTeams = getNumTeams("data.txt");
        boolean winner;
        Scanner inFile = new Scanner(new File("data.txt"));
        String[] names = new String[numberOfTeams]; 
        double[][] scores = new double[5][numberOfTeams];
        //all team arrays
        double[] averages = new double[5];
        double[] maxVals = new double[5];
        double[] minVals = new double[5];
        //winning arrays
        double[][] winners = new double[5][numberOfTeams];
        double[] avgWins = new double[5];
        double[] maxWins = new double[5];
        double[] minWins = new double[5];
        //losing arrays
        double[][] losers = new double[5][numberOfTeams];
        double[] avgLos = new double[5];
        double[] maxLos = new double[5];
        double[] minLos = new double[5];
        
        //initialize vales of maxVals and minVals
        for (int i = 0; i < maxVals.length; i++)
        {
            maxVals[i] = Integer.MIN_VALUE;
            minVals[i] = Integer.MAX_VALUE;
            minWins[i] = Integer.MAX_VALUE;
            maxWins[i] = Integer.MIN_VALUE;
            minLos[i] = Integer.MAX_VALUE;
            maxLos[i] = Integer.MIN_VALUE;
        }
        
        scores = readFile("data.txt");
        analyze(scores, averages, maxVals, minVals, numberOfTeams);
        winners = readFile("data.txt", true);
        analyze(winners, avgWins, maxWins, minWins, winners[0].length);
        losers = readFile("data.txt", false);
        analyze(losers, avgLos, maxLos, minLos, losers[0].length);
        
        //output
        System.out.println("ALL TEAMS:");
        System.out.printf("\t %10s \t %10s \t %10s \t %10s \t %10s\n", "Week 1", "Week 2", "Week 3", "Week 4", "Week 5");
        System.out.printf("MIN\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", minVals[0], minVals[1], minVals[2], minVals[3], minVals[4]);
        System.out.printf("MAX\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", maxVals[0], maxVals[1], maxVals[2], maxVals[3], maxVals[4]);
        System.out.printf("AVG\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", averages[0], averages[1], averages[2], averages[3], averages[4]);
        System.out.println("\nWINNERS:");
        System.out.printf("MIN\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", minWins[0], minWins[1], minWins[2], minWins[3], minWins[4]);
        System.out.printf("MAX\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", maxWins[0], maxWins[1], maxWins[2], maxWins[3], maxWins[4]);
        System.out.printf("AVG\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", avgWins[0], avgWins[1], avgWins[2], avgWins[3], avgWins[4]);
        System.out.println("\nLOSERS:");
        System.out.printf("MIN\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", minLos[0], minLos[1], minLos[2], minLos[3], minLos[4]);
        System.out.printf("MAX\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", maxLos[0], maxLos[1], maxLos[2], maxLos[3], maxLos[4]);
        System.out.printf("AVG\t %10.2f \t %10.2f \t %10.2f \t %10.2f \t %10.2f\n", avgLos[0], avgLos[1], avgLos[2], avgLos[3], avgLos[4]);
    }
    /**
     * Gets the number of teams from the file
     * @param fileName
     * @return the number of teams
     * @throws FileNotFoundException
     */
    public static int getNumTeams(String fileName) throws FileNotFoundException
    {
        Scanner inFile = new Scanner(new File (fileName));
        
        int count = 0;
        int numTeams = 0;
        
        while (inFile.hasNextLine())
        {
            count++; //get total number of lines
            inFile.nextLine();
        }
        
        numTeams = count / 7; //number of teams is total divided by 7 (because 7 lines per team)
        return numTeams;
    }
    /**
     * Reads in the data from the file and puts it into an array
     * @param fileName The name of the file to read in
     * @return the 2d array holding all statistics from the file
     * @throws FileNotFoundException
     */
    public static double[][] readFile(String fileName) throws FileNotFoundException
    {
        Scanner inFile = new Scanner(new File (fileName));
        //declare new arrays
        double[][] scores = new double[5][getNumTeams(fileName)];
        boolean[] winners = new boolean[getNumTeams(fileName)];
        
        //declare scores to find
        double score1;
        double score2;
        double score3;
        double score4;
        double score5;
        
        //get input
        int countRow = 0;
        int countCol = 0;
        while (inFile.hasNextLine()) //while file still has lines
        {
            countRow = 0; //start inputting at the first row
            inFile.nextLine(); //skip over the name
            score1 = Double.parseDouble(inFile.nextLine()); //get the first stat
            scores[countRow][countCol] = score1; //put the first score into score array
            countRow++; //go to next row in score array
            //repeat for each score
            score2 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score2;
            countRow++;
            score3 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score3;
            countRow++;
            score4 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score4;
            countRow++;
            score5 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score5;
            String winLose = inFile.nextLine(); //get the TRUE/FALSE line of the file
            boolean winAsBool = true; //variable to hold whether or not the team was a winner
            if (winLose.equals("TRUE"))
                winAsBool = true; //winner
            else if (winLose.equals("FALSE"))
                winAsBool = false; //loser
            winners[countCol] = winAsBool; //add winner or loser to winners array
            countCol++; 
        }
        return scores;  
    }
    /**
     * Reads in data from file and puts all winner or all loser statistics in an array
     * @param fileName The name of the file
     * @param winner True or false to find either winner array or loser array 
     * @return the array of winners or losers
     * @throws FileNotFoundException
     */
    public static double[][] readFile(String fileName, boolean winner) throws FileNotFoundException
    {
        //Same as readFile
        Scanner inFile = new Scanner(new File (fileName));
        double[][] scores = new double[5][getNumTeams(fileName)];
        boolean[] wins = new boolean[getNumTeams(fileName)];
        
        double score1 = 0;
        double score2 = 0;
        double score3 = 0;
        double score4 = 0;
        double score5 = 0;
        
        //get input
        int countRow = 0;
        int countCol = 0;
        while (inFile.hasNextLine()) //while file still has lines
        {
            countRow = 0;
            inFile.nextLine();
            score1 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score1;
            countRow++;
            score2 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score2;
            countRow++;
            score3 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score3;
            countRow++;
            score4 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score4;
            countRow++;
            score5 = Double.parseDouble(inFile.nextLine());
            scores[countRow][countCol] = score5;
            String winLose = inFile.nextLine();
            boolean winAsBool = winner;
            if (winLose.equals("TRUE"))
                winAsBool = true;
            else if (winLose.equals("FALSE"))
                winAsBool = false;
            wins[countCol] = winAsBool;
            countCol++; 
        }
        
        //create the winner array or loser array
        scores = createWinLoseArray(wins, scores, winner);
        return scores;        
    }
    /**
     * Creates an array for the winners or losers
     * @param wins The array that holds true false values for all teams
     * @param data The scores to choose winners or losers from
     * @param winner True or false value to find either winners or losers
     * @return
     */
    public static double[][] createWinLoseArray(boolean[] wins, double[][] data, boolean winner)
    {
        //get the number of winners or losers
        int lengthOfNewArray = 0;
        for (int i = 0; i < wins.length; i++)
        {
            if (wins[i] == winner)
                lengthOfNewArray++;
        }
        
        //declare new array for winners or losers
        double[][] wins2D = new double[5][lengthOfNewArray];
        int nextColIn2D = 0;
        //set the data from scores that matches to winners or losers to new winners or losers array
        for (int i = 0; i < wins.length; i++)
        {
            if (wins[i] == winner)
            {
                for (int countRow = 0; countRow < wins2D.length; countRow++)
                { 
                    wins2D[countRow][nextColIn2D] = data[countRow][i]; 
                }
                nextColIn2D++;
            }
        }
        return wins2D;
    }
    /**
     * Finds average, max, and min or data set
     * @param data The 2d array to analyze
     * @param averages The array of averages for all stats
     * @param maxes The array of max values for all stats
     * @param mins The array of min values for all stats
     * @param numTeams The number of teams in the data array
     */
    public static void analyze(double[][] data, double[] averages, double[] maxes, double[] mins, int numTeams)
    {
        int total = 0;
        //find max and min values to store in arrays
        for (int score = 0; score < data.length; score++)
        {
            total = 0;
            for (int team = 0; team < data[score].length; team++)
            {
                if (data[score][team] > maxes[score])
                    maxes[score] = data[score][team];
                if (data[score][team] < mins[score])
                    mins[score] = data[score][team];
                total += data[score][team];
            }
            //get average
            averages[score] = total / (double)numTeams;
        }
    }
    
}
