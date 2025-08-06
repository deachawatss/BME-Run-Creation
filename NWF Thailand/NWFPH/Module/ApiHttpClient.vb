Imports System.Net.Http
Imports System.Text

Module ApiHttpClient
    Sub Main()
        ' URL to which you want to send the POST request
        Dim url As String = "http://192.168.1.38/bme_api/"

        ' Data to be sent in the POST request (as a key-value pair)
        Dim postData As New Dictionary(Of String, String) From {
            {"key1", "value1"},
            {"key2", "value2"}
        }

        ' Create a string representation of the POST data
        Dim postDataString As String = String.Join("&", postData.Select(Function(kv) $"{kv.Key}={kv.Value}"))

        ' Create an instance of HttpClient
        Using httpClient As New HttpClient()
            ' Create the content to be sent in the POST request
            Dim content As New StringContent(postDataString, Encoding.UTF8, "application/x-www-form-urlencoded")

            ' Send the POST request
            Dim response As HttpResponseMessage = httpClient.PostAsync(url, content).Result

            ' Check if the request was successful
            If response.IsSuccessStatusCode Then
                ' Read and display the response content
                Dim result As String = response.Content.ReadAsStringAsync().Result
                Console.WriteLine("Response: " & result)
            Else
                Console.WriteLine("Error: " & response.StatusCode.ToString() & " - " & response.ReasonPhrase)
            End If
        End Using
    End Sub

End Module
