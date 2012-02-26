using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Text;
using NativeWifi;

class Program
{

    static void Main(string[] args)
    {
        WlanClient client = new WlanClient();
        try
        {
            foreach (WlanClient.WlanInterface wlanIface in client.Interfaces)
            {
                Wlan.WlanBssEntry[] wlanBssEntries = wlanIface.GetNetworkBssList();
                Dictionary<string, List<String>> dictionary = new Dictionary<string, List<String>>();
                foreach (Wlan.WlanBssEntry network in wlanBssEntries)
                {
                    int rss = network.rssi;
                    byte[] macAddr = network.dot11Bssid;

                    string tMac = "";
                    for (int i = 0; i < macAddr.Length; i++){
                        tMac += macAddr[i].ToString("x2").PadLeft(2, '0').ToUpper();
                    }
                    string ssid = System.Text.ASCIIEncoding.ASCII.GetString(network.dot11Ssid.SSID).ToString().Replace(((char)0) + "", ""); //replace null chars
                    string dataString = "MAC:" + tMac + ",Signal:" + network.linkQuality + ",RSSID:" + rss.ToString();
                    if (dictionary.ContainsKey(ssid))
                    {
                        dictionary[ssid].Add(dataString);
                    }
                    else
                    {
                        //there must be a more efficient/ better pattern in C#...
                        List<String> tList = new List<String>();
                        tList.Add(dataString);
                        dictionary.Add(ssid,tList);
                    }
                }
                foreach (String ssid in dictionary.Keys)
                {
                    Console.Write(ssid + ":{[" + String.Join("],[",dictionary[ssid]) + "]}"); //TODO: more elegant solution for output
                    Console.WriteLine("\n");
                }
                Console.ReadLine();
            }
            
        }
        catch (Exception ex)
        {
            //lala
        }

    }
}
