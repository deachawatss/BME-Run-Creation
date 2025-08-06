Imports System.Net.Http
Imports System.Text
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq
Public Class ApiClass
    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Public Function post_request(ByVal myapi As String, ByVal postData As Dictionary(Of String, String)) As JContainer
        Dim nwfserver As Object = myreg("nwf_server")
        Dim url = "http://192.168.1.38/bme_api/api/" + myapi

        'If nwfserver = "192.168.1.39" Then
        '    url = "http://192.168.1.39:81/bme/api/" + myapi
        'Else
        '    url = "http://192.168.1.38/bme_api/api/" + myapi
        'End If

        Dim user = UserInfo.getUserinfo()



        Dim postDataString As String
        If postData IsNot Nothing Then
            postDataString = String.Join("&", postData.Select(Function(kv) $"{kv.Key}={kv.Value}"))
        Else
            postDataString = ""
        End If


        If user IsNot Nothing Then
            postDataString &= "&uname=" & user.Item("uname") & "&nwfph-session=" & user.Item("nwfphsession")
        End If


        ' Create an instance of HttpClient
        Using httpClient As New HttpClient()
            Dim content As New StringContent(postDataString, Encoding.UTF8, "application/x-www-form-urlencoded")

            Dim response As HttpResponseMessage = httpClient.PostAsync(url, content).Result

            If response.IsSuccessStatusCode Then
                Dim responseContent As String = response.Content.ReadAsStringAsync().Result

                ' Try to parse as JArray
                Try
                    Return JArray.Parse(responseContent)
                Catch ex As JsonReaderException
                    ' If parsing as JArray fails, try parsing as JObject
                    Try
                        Return JObject.Parse(responseContent)
                    Catch ex2 As Exception
                        ' Handle any other exceptions
                        MsgBox("Oppss Something went wrong, Please contact the administrator", MsgBoxStyle.Critical, "Error Code: 2")
                        Throw New Exception($"Error parsing response: {ex2.Message}")
                    End Try
                End Try
            Else
                ' You may want to handle errors differently here
                MsgBox("Oppss Something went wrong, Please contact the administrator", MsgBoxStyle.Critical, "Error Code: 1")
                Throw New Exception($"Error: {response.StatusCode} - {response.ReasonPhrase}")
            End If
        End Using
    End Function
End Class
