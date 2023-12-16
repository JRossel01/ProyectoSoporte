package org.example;

import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Reducer;

import java.io.IOException;

public class ChatGPTReducer extends Reducer<Text, Text, Text, Text> {
    public void reduce(Text key, Iterable<Text> values, Context context) throws IOException, InterruptedException {
        StringBuilder combinedValues = new StringBuilder();
        for (Text value : values) {
            combinedValues.append(" | ").append(value.toString());
        }
        context.write(key, new Text(combinedValues.toString()));
    }
}