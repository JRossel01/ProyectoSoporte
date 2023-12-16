package org.example;

import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Mapper;

import java.io.IOException;

public class ChatGPTMapper extends Mapper<LongWritable, Text, Text, Text> {
    public void map(LongWritable key, Text value, Context context) throws IOException, InterruptedException {
        String line = value.toString();
        String result = ChatGPTAPIExample.chatGPT("Hay dos tipos de mensajes, las resenhas y las preguntas sobre productos.  " +
                "Si el mensaje es una resenha SOLO debes responder este formato, si la resenha es positiva o negativa debes asignarle una" +
                "calificacion entre -10 y 10, la respuesta debe ser asi: Resenha | Calificacion." +
                "Si el mensaje es una pregunta sobre un producto debes responder asi: Producto | NombreDelProducto" +
                " Esa deben ser las unicas respuestas, no quiero que anhadas mas texto, el mensaje es la siguiente: " + line);
        context.getCounter("ChatGPT", "Responses").increment(1);
        context.write(new Text(line), new Text(result));
    }
}