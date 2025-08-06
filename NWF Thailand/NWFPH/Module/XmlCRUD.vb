Imports System.Xml
Imports System.Collections.Generic

Module XmlCRUD
    Dim filePath As String = "NWFTHDATA.xml"
    Sub Main()

    End Sub

    Sub ConvertDictionaryToXmlFile(ByVal dictionary As Dictionary(Of String, Object))

        Dim xmlDoc As New XmlDocument()
        Dim rootElement As XmlElement = xmlDoc.CreateElement("Root")
        xmlDoc.AppendChild(rootElement)

        AddDictionaryToXmlElement(dictionary, rootElement, xmlDoc)

        xmlDoc.Save(filePath)
    End Sub

    Sub AddDictionaryToXmlElement(ByVal dictionary As Dictionary(Of String, Object), ByVal parentElement As XmlElement, ByVal xmlDoc As XmlDocument)
        For Each kvp As KeyValuePair(Of String, Object) In dictionary
            Dim element As XmlElement = xmlDoc.CreateElement(kvp.Key)
            parentElement.AppendChild(element)

            If TypeOf kvp.Value Is Dictionary(Of String, Object) Then
                AddDictionaryToXmlElement(DirectCast(kvp.Value, Dictionary(Of String, Object)), element, xmlDoc)
            Else
                element.InnerText = kvp.Value.ToString()
            End If
        Next
    End Sub

    Function ReadXmlFileToDictionary() As Dictionary(Of String, Object)
        Dim xmlDoc As New XmlDocument()
        Try
            xmlDoc.Load(filePath)

            Dim dictionary As New Dictionary(Of String, Object)()
            ParseXmlNode(xmlDoc.DocumentElement, dictionary)

            Return dictionary
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error reading XML file: " & ex.Message)
            Return Nothing
        End Try
    End Function

    Sub ParseXmlNode(ByVal node As XmlNode, ByVal dictionary As Dictionary(Of String, Object))
        For Each childNode As XmlNode In node.ChildNodes

            If childNode.NodeType = XmlNodeType.Element Then
                'If childNode.HasChildNodes Then
                'Dim childDictionary As New Dictionary(Of String, Object)()
                'ParseXmlNode(childNode, childDictionary)
                'dictionary(childNode.Name) = childDictionary
                'Else
                'dictionary(childNode.Name) = childNode.InnerText
                'End If
                'dictionary(childNode.Name) = childNode.InnerText
                dictionary.Add(childNode.Name, childNode.InnerText)
            End If

        Next
    End Sub
End Module
