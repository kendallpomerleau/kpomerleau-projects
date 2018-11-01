/**
 * CSE 132 - Assignment 8
 * 
 * Name: Kendall Pomerleau
 * WUSTL Key: 457626
 * 
 */
 
#include <Wire.h>
#include <SparkFun_MMA8452Q.h> 
#include <time.h>
#include <stdlib.h>
#include <stdio.h>

MMA8452Q accel;

int ROW_ON = HIGH;
int ROW_OFF = LOW;
int COL_ON = LOW;
int COL_OFF = HIGH;

int rowArray[] = {2, 3, 4, 5, 6, 7, 8};
int colArray[] = {9, 10, 11, 12, 13};

//delta timing for time elapsed
int timeElapsed = 0;
int oneSecond = 1000;
int halfSecond = 500;
unsigned long lastCountedSecond = 0;
unsigned long lastPreyMove = 0;
unsigned long lastPredatorMove = 0;

//delta timing for blinking
int timeBetweenBlinks = 125;
unsigned long lastBlink = 0;

//locations of prey and predator
int preyLocation[2];
int predatorLocation[2];
int predLED = -1;

//user input
int myVar = 0;

//overall scores
int preyWins = 0;
int predatorWins = 0;

//current winner
int winner = 0;
int loser = 0;

//delta timing for showing scores
int delayTime = 4000;
int nextTime = 0;

//timing constants
const int ROUND_TIME = 15;
const int MIN_TIME_BETWEEN_ROUNDS = 4005;

void setup() {
  Serial.begin(9600);
  //set up pins
  pinMode(2, OUTPUT);
  pinMode(3, OUTPUT);
  pinMode(4, OUTPUT);
  pinMode(5, OUTPUT);
  pinMode(6, OUTPUT);
  pinMode(7, OUTPUT);
  pinMode(8, OUTPUT);
  pinMode(9, OUTPUT);
  pinMode(10, OUTPUT);
  pinMode(11, OUTPUT);
  pinMode(12, OUTPUT);
  pinMode(13, OUTPUT);
  for (int r = 0; r < 7; r++) {
    digitalWrite(rowArray[r], ROW_OFF); //turn all rows off
  }

  for (int c = 0; c < 5; c++) {
    digitalWrite(colArray[c], COL_OFF); //turn all columns on
  }
  initializeLocations();
  accel.init();
}

void loop() {
  int now = millis();
  if (timeElapsed >= ROUND_TIME || predContainsPrey()) {
    //game over
    
    //update beginning of delay
    static unsigned long beginningOfDelay = now;
    if (now - beginningOfDelay > MIN_TIME_BETWEEN_ROUNDS) { 
      //another round has happened
      //redeclare beginning of delay
      beginningOfDelay = now;
    }
    
    if ((now - beginningOfDelay) < delayTime) {
      //still delaying

      //predator wins
      if (now == beginningOfDelay && !predContainsPrey()) {
        //prey is winner
        beginningOfDelay--;
        preyWins++;
        winner = preyWins;
        loser = predatorWins;
      }
      //prey wins
      else if (now == beginningOfDelay && predContainsPrey()) {
        //predator is winner
        beginningOfDelay--;
        predatorWins++;
        winner = predatorWins;
        loser = preyWins;
      }
      
      //loop to display prey/predator at mid screen
      for (int col = 0; col < 5; col++) {
        digitalWrite(colArray[col], COL_ON); //turn on column to be focused on
        for (int col2 = 0; col2 < 5; col2++) {
          if (col != col2) {
            digitalWrite(colArray[col2], COL_OFF);
          }
        }

        //turn on rows that correspond to the right column
        for (int row = 0; row < 7; row++) {
          if (col == 0) {
            if ((loser - winner) > 2) {
              if (row == 6) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
          }
          else if (col == 1) {
            if ((loser - winner) > 0) {
              if (row == 6) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
          }
          else if (col == 2) {
            if (row == 6 || row == 3) {
              digitalWrite(rowArray[row], ROW_ON);
            }
    
            if (predContainsPrey()) {
              if (row == 4) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
          }
          else if (col == 3) {
            if ((winner - loser) > 0) {
              if (row == 6) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
            
            if (predContainsPrey()) {
              if (row == 4 || row == 3) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
          }
          else if (col == 4) {
            if ((winner - loser) > 2) {
              if (row == 6) {
                digitalWrite(rowArray[row], ROW_ON);
              }
            }
          }
        }//end row loop
        for (int row = 0; row < 7; row++) {
          digitalWrite(rowArray[row], ROW_OFF);
        }
      }//end column loop
    }
    else {
      timeElapsed = 0;
      initializeLocations();
    }
  }
  else {
    
    //display and blink predator
    for (int col = 0; col < 5; col++) {
      if (col == predatorLocation[0] || col == predatorLocation[0]+1) {
        if ((now - lastBlink) > timeBetweenBlinks) {
          //time for next blink
          if (predLED == COL_ON) {
            digitalWrite(colArray[col], COL_OFF);
            predLED = COL_OFF;
          }
          else{
            digitalWrite(colArray[col], COL_ON);
            predLED = COL_ON;
          }
          lastBlink = now;
        }
        else {
          digitalWrite(colArray[col], predLED);
          digitalWrite(rowArray[preyLocation[1]], ROW_OFF);
          for (int col2 = 0; col2 < 5; col2++) {
            if (col != col2) {
              digitalWrite(colArray[col2], COL_OFF);
            }
          }
        }
        for (int row = 0; row < 7; row++) {
          if (row == predatorLocation[1] || row == predatorLocation[1]+1) {
            digitalWrite(rowArray[row], ROW_ON);
          }
          else {
            digitalWrite(rowArray[row], ROW_OFF);
          }
        }
      }
      for (int row = 0; row < 7; row++) {
        digitalWrite(rowArray[row], ROW_OFF);
      }
    }//end column loop

    //display prey
    for (int col = 0; col < 5; col++){
      if (col == preyLocation[0]) {
        digitalWrite(colArray[col], COL_ON);
        for (int col2 = 0; col2 < 5; col2++) {
          if (col != col2) {
            digitalWrite(colArray[col2], COL_OFF);
          }
        }
        for (int row = 0; row < 7; row++) {
          if (row == preyLocation[1]) {
            digitalWrite(rowArray[row], ROW_ON);
          }
          else {
            digitalWrite(rowArray[row], ROW_OFF);
          }
        }
      } 
    }


    //move prey forward, back, left, or right
    if ((now - lastPreyMove) > halfSecond) {
      //sufficient time has passed since last prey move
      if (accel.available()) {
        accel.read();
        if (accel.cy > .2) {
          movePreyForward();
        }
        else if (accel.cy < -.2) {
          movePreyBackward();
        }
    
        if (accel.cx > .2) {
          movePreyRight();
        }
        else if (accel.cx < -.2) {
          movePreyLeft();
        }

        lastPreyMove = now;
      }
    }

    //move predator based on user input
    if ((now - lastPredatorMove) > oneSecond) {
      if (Serial.available() > 0) {
        myVar = Serial.read();
        if (myVar == 'l') {
          movePredatorLeft();
          lastPredatorMove = now;
        }
        else if (myVar == 'r') {
          movePredatorRight();
          lastPredatorMove = now;
        }
        if (myVar == 'u') {
          movePredatorForward();
          lastPredatorMove = now;
        }
        else if (myVar == 'd') {
          movePredatorBackward();
          lastPredatorMove = now;
        }
      }
    }
  }
  
  //delta timing to increment number of seconds
  if ((now - lastCountedSecond) > oneSecond) {
    timeElapsed++;
    lastCountedSecond = now;
  }

}

/**
 * Returns true if prey is inside the predator
 * Returns false otherwise
 */
bool predContainsPrey() {
  if (preyLocation[0] == predatorLocation[0] && preyLocation[1] == predatorLocation[1]) {
    return true;
  }
  else if (preyLocation[0] == predatorLocation[0] && preyLocation[1] == predatorLocation[1]+1) {
    return true;
  }
  else if (preyLocation[0] == predatorLocation[0]+1 && preyLocation[1] == predatorLocation[1])  {
    return true;
  }
  else if (preyLocation[0] == predatorLocation[0]+1 && preyLocation[1] == predatorLocation[1]+1) {
    return true;
  }
  return false;
}

/**
 * Prey moves forward
 * Only if not at edge of board
 */
void movePreyForward(){
  if (preyLocation[1] != 0)
    preyLocation[1] = preyLocation[1]-1;
}

/**
 * Prey moves backward
 * Only if not at edge of board
 */
void movePreyBackward() {
  if (preyLocation[1] != 6)
    preyLocation[1] = preyLocation[1]+1;
}

/**
 * Prey moves right
 * Only if not at edge of board
 */
void movePreyRight() {
  if (preyLocation[0] != 4)
    preyLocation[0] = preyLocation[0]+1;
}

/**
 * Prey moves left
 * Only if not at edge of board
 */
void movePreyLeft() {
  if (preyLocation[0] != 0)
    preyLocation[0] = preyLocation[0]-1;
}

/**
 * Predator moves forward
 * Only if not at edge of board
 */
void movePredatorForward() {
  if (predatorLocation[1] != 0)
    predatorLocation[1] = predatorLocation[1]-1;
}

/**
 * Predator moves backward
 * Only if not at edge of board
 */
void movePredatorBackward() {
  if (predatorLocation[1] != 5)
    predatorLocation[1] = predatorLocation[1]+1;
}

/**
 * Predator moves right
 * Only if not at edge of board
 */
void movePredatorRight() {
  if (predatorLocation[0] != 3)
    predatorLocation[0] = predatorLocation[0]+1;
}

/**
 * Predator moves left
 * Only if not at edge of board
 */
void movePredatorLeft() {
  if (predatorLocation[0] != 0)
    predatorLocation[0] = predatorLocation[0]-1;
}

void initializeLocations(){
  predLED = COL_ON;
  preyLocation[0] = 0;
  predatorLocation[0] = 0;
  preyLocation[1] = 0;
  predatorLocation[1] = 0;
  while (predContainsPrey()) {
    preyLocation[0] = rand() % 5;
    predatorLocation[0] = rand() % 4;
    preyLocation[1] = rand() % 7;
    predatorLocation[1] = rand() % 6;
  }
}


