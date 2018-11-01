import java.io.BufferedReader;
import java.io.InputStreamReader;
import jssc.SerialPortException;


public class PCPlayer {
	private static long timeElapsed = 0;
	private static int MEASUREMENT_DELAY = 1000;
	private static long lastMeasurement = 0;
	public static void main(String[] args) throws SerialPortException {
		SerialComm port = new SerialComm("/dev/cu.usbserial-DN02BALP");
		while(true) {
			timeElapsed = System.currentTimeMillis();
			if (timeElapsed - lastMeasurement > MEASUREMENT_DELAY) {
				
				if (StdDraw.isKeyPressed(37)) {
					port.writeByte((byte) 'l');
					System.out.println("left");
				}
				if (StdDraw.isKeyPressed(38)) {
					port.writeByte((byte) 'u');
					System.out.println("up");
				}
				if (StdDraw.isKeyPressed(39)) {
					port.writeByte((byte) 'r');
					System.out.println("right");
				}
				if (StdDraw.isKeyPressed(40)) {
					port.writeByte((byte) 'd');
					System.out.println("down");
				}
				lastMeasurement = timeElapsed;
			} //end delta timing 
		}
	}
}