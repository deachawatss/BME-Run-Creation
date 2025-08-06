Imports MySql.Data.MySqlClient
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq

Public Class MySQL

    Private connectionString As String
    Private connection As MySqlConnection
    Dim mysec As New EncryptionHelper()
    Public Sub New()
        Dim myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
        Dim dbuname As Object = myreg("nwf_uname")
        Dim dbpword As Object = mysec.DecryptString(myreg("nwf_pword"))
        Dim dbport As Object = myreg("nwf_port")
        Dim dbname As Object = myreg("nwf_database")
        Dim nwfserver As Object = myreg("nwf_server")


        connectionString = String.Format("server={0};user id={1};password={2};database={3};port={4}", nwfserver, dbuname, dbpword, dbname, dbport)
        connection = New MySqlConnection(connectionString)
    End Sub

    'Create operation
    Public Function Create(ByVal tableName As String, ByVal data As Dictionary(Of String, String)) As Integer
        Dim result As Integer = 0
        Try
            connection.Open()
            Dim sql As String = "INSERT INTO " & tableName & "(" & String.Join(",", data.Keys) & ") VALUES ('" & String.Join("','", data.Values) & "'); SELECT LAST_INSERT_ID();"

            'Dim command As New MySqlCommand(sql, connection)
            'result = command.ExecuteNonQuery()
            Dim command As New MySqlCommand(sql, connection)
            result = Convert.ToInt32(command.ExecuteScalar())

        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error creating data: " & ex.Message)
        Finally
            connection.Close()
        End Try
        Return result
    End Function

    'Read operation
    Public Function Read(ByVal tableName As String, Optional ByVal columns As String = "*", Optional ByVal where As String = "") As MySqlDataReader
        Dim reader As MySqlDataReader = Nothing
        Try
            connection.Open()
            Dim sql As String = "SELECT " & columns & " FROM " & tableName
            If where <> "" Then
                sql &= " WHERE " & where
            End If
            Dim command As New MySqlCommand(sql, connection)
            reader = command.ExecuteReader()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error reading data: " & ex.Message)
        Finally
            connection.Close()
        End Try
        Return reader
    End Function

    'Update operation
    Public Function Update(ByVal tableName As String, ByVal data As Dictionary(Of String, String), ByVal where As String) As Integer
        Dim result As Integer = 0
        Try
            connection.Open()
            Dim sql As String = "UPDATE " & tableName & " SET " & String.Join(",", data.Select(Function(kv) String.Format("{0}='{1}'", kv.Key, kv.Value))) & " WHERE " & where
            Dim command As New MySqlCommand(sql, connection)
            result = command.ExecuteNonQuery()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error updating data: " & ex.Message)
        Finally
            connection.Close()
        End Try
        Return result
    End Function

    'Delete operation
    Public Function Delete(ByVal tableName As String, ByVal where As String) As Integer
        Dim result As Integer = 0
        Try
            connection.Open()
            Dim sql As String = "DELETE FROM " & tableName & " WHERE " & where
            Dim command As New MySqlCommand(sql, connection)
            result = command.ExecuteNonQuery()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error deleting data: " & ex.Message)
        Finally
            connection.Close()
        End Try
        Return result
    End Function

    'Select operation
    Public Function SelectData(ByVal sql As String) As JArray
        Dim reader As MySqlDataReader = Nothing
        Dim mylist As New List(Of Dictionary(Of String, Object))()
        Dim myarray As JArray = Nothing
        Try
            connection.Open()
            Dim command As New MySqlCommand(sql, connection)
            reader = command.ExecuteReader()


            While reader.Read()
                Dim llist As New Dictionary(Of String, Object)()
                For i As Integer = 0 To reader.FieldCount - 1
                    llist.Add(reader.GetName(i), reader.GetValue(i))

                Next

                mylist.Add(llist)
            End While

            Dim json = JsonConvert.SerializeObject(mylist)
            myarray = JArray.Parse(json)


        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error executing SQL: " & ex.Message)

        Finally
            connection.Close()
        End Try

        Return myarray
    End Function

    'Read Scalar
    Public Function SelectDataScalar(ByVal sql As String) As Dictionary(Of String, Object)
        Dim reader As MySqlDataReader = Nothing
        Dim row As New Dictionary(Of String, Object)

        Try
            connection.Open()
            Dim command As New MySqlCommand(sql, connection)
            reader = command.ExecuteReader()
            If reader.Read() Then
                For i As Integer = 0 To reader.FieldCount - 1
                    row.Add(reader.GetName(i), reader.GetValue(i))
                Next
            End If
            reader.Close()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error reading data: " & ex.Message)
        Finally
            connection.Close()
        End Try

        Return row
    End Function

    'Read Scalar
    Public Function ReadScalar(ByVal tableName As String, Optional ByVal columns As String = "*", Optional ByVal where As String = "") As Dictionary(Of String, Object)
        Dim reader As MySqlDataReader = Nothing
        Dim row As New Dictionary(Of String, Object)

        Try
            connection.Open()
            Dim sql As String = "SELECT " & columns & " FROM " & tableName
            If where <> "" Then
                sql &= " WHERE " & where
            End If
            sql &= " Limit 1"
            Dim command As New MySqlCommand(sql, connection)
            reader = command.ExecuteReader()
            If reader.Read() Then
                For i As Integer = 0 To reader.FieldCount - 1
                    row.Add(reader.GetName(i), reader.GetValue(i))
                Next
            End If
            reader.Close()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error reading data: " & ex.Message)
        Finally
            connection.Close()
        End Try

        Return row
    End Function
End Class