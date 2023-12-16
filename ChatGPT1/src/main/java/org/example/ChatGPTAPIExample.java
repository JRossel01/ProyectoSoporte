package org.example;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
public class ChatGPTAPIExample {

    public static String chatGPT(String prompt) {
        String url = "https://api.openai.com/v1/chat/completions";
        String apiKey = "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";
        String model = "gpt-3.5-turbo";

        try {
            URL obj = new URL(url);
            HttpURLConnection connection = (HttpURLConnection) obj.openConnection();
            connection.setRequestMethod("POST");
            connection.setRequestProperty("Authorization", "Bearer " + apiKey);
            connection.setRequestProperty("Content-Type", "application/json");

            // The request body
            String body = "{\"model\": \"" + model + "\", \"messages\": [{\"role\": \"user\", \"content\": \"" + prompt + "\"}]}";
            connection.setDoOutput(true);
            OutputStreamWriter writer = new OutputStreamWriter(connection.getOutputStream());
            writer.write(body);
            writer.flush();
            writer.close();

            // Response from ChatGPT
            int responseCode = connection.getResponseCode();
            // System.out.println("Response Code: " + responseCode);

            BufferedReader br;
            if (responseCode == HttpURLConnection.HTTP_OK) {
                br = new BufferedReader(new InputStreamReader(connection.getInputStream(), StandardCharsets.UTF_8));
            } else {
                br = new BufferedReader(new InputStreamReader(connection.getErrorStream(), StandardCharsets.UTF_8));
            }

            String line;
            StringBuilder response = new StringBuilder();

            while ((line = br.readLine()) != null) {
                response.append(line);
            }
            br.close();

            System.out.println("API Response: " + response.toString());

            // calls the method to extract the message.
            return extractMessageFromJSONResponse(response.toString());

        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public static String extractMessageFromJSONResponse(String response) {
        try {
            byte[] utf8Bytes = response.getBytes("UTF-8");
            String utf8String = new String(utf8Bytes, StandardCharsets.UTF_8);

            int start = utf8String.indexOf("content") + 11;
            int end = utf8String.indexOf("\"", start);

            return utf8String.substring(start, end);
        } catch (UnsupportedEncodingException e) {
            throw new RuntimeException("Error decoding UTF-8", e);
        }
    }

    public static void main(String[] args) {
        System.out.println(chatGPT("hello, how are you? Can you tell me what's a Fibonacci Number?"));
    }
}
