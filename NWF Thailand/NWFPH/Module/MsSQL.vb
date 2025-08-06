Imports System.Data.SqlClient
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq

Public Class MsSQL
    Private connectionString As String
    Private connection As SqlConnection
    Private mycon As SqlTransaction
    Dim mysec As New EncryptionHelper()
    Public Sub New(Optional ByVal app As String = "BME")
        Dim myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()

        If app Is "BME" Then
            Dim dbuname As Object = myreg("bme_uname")
            Dim dbpword As Object = mysec.DecryptString(myreg("bme_pword"))
            Dim dbname As Object = myreg("bme_database")
            Dim nwfserver As Object = myreg("bme_server")
            connectionString = String.Format("Server={0},1433;Database={3};User Id={1};Password={2}", nwfserver, dbuname, dbpword, dbname)
        Else
            Dim dbuname As Object = myreg("nwf_uname")
            Dim dbpword As Object = myreg("nwf_pword")
            Dim dbport As Object = myreg("nwf_port")
            Dim dbname As Object = myreg("nwf_database")
            Dim nwfserver As Object = myreg("nwf_server")
            connectionString = String.Format("Server={0},1433;Database={3};User Id={1};Password={2}", nwfserver, dbuname, dbpword, dbuname)
        End If

        connection = New SqlConnection(connectionString)
    End Sub

    'Start Transaction
    Public Sub TransStart()

    End Sub

    'Commit Transaction
    Public Sub TransCommit()

    End Sub
    'Create operation
    Public Function Create(ByVal tableName As String, ByVal data As Dictionary(Of String, String)) As Integer
        Dim result As Integer = 0
        Try
            connection.Open()

            Dim sql As String = "INSERT INTO " & tableName & " (" & String.Join(",", data.Keys) & ") VALUES ('" & String.Join("','", data.Values) & "'); SELECT SCOPE_IDENTITY();"

            'Dim command As New SqlCommand(sql, connection)
            'result = command.ExecuteNonQuery()
            Dim command As New SqlCommand(sql, connection)
            result = Convert.ToInt32(command.ExecuteScalar())
            Debug.WriteLine(sql)
        Catch ex As Exception
            LogError(ex)
            Debug.WriteLine("Error creating data: " & ex.Message)
        Finally
            connection.Close()
        End Try
        Return result
    End Function

    'Read operation
    Public Function Read(ByVal tableName As String, Optional ByVal columns As String = "*", Optional ByVal where As String = "") As SqlDataReader
        Dim reader As SqlDataReader = Nothing

        Try
            connection.Open()
            Dim sql As String = "SELECT " & columns & " FROM " & tableName
            If where <> "" Then
                sql &= " WHERE " & where
            End If
            Dim command As New SqlCommand(sql, connection)
            reader = command.ExecuteReader()

        Catch ex As Exception
            LogError(ex)
            Debug.WriteLine("Error reading data: " & ex.Message)
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
            Dim command As New SqlCommand(sql, connection)
            result = command.ExecuteNonQuery()
            Debug.WriteLine(sql)
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
            Dim command As New SqlCommand(sql, connection)
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
        Dim mylist As New List(Of Dictionary(Of String, Object))()
        Dim myarray As JArray = Nothing
        Try
            connection.Open()
            Dim command As New SqlCommand(sql, connection)
            Dim reader As SqlDataReader = command.ExecuteReader()

            If reader.FieldCount > 0 Then
                While reader.Read()

                    Dim llist As New Dictionary(Of String, Object)()
                    For i As Integer = 0 To reader.FieldCount - 1
                        llist.Add(reader.GetName(i), reader.GetValue(i))

                    Next

                    mylist.Add(llist)
                End While

                Dim json = JsonConvert.SerializeObject(mylist)
                myarray = JArray.Parse(json)
            End If
            reader.Close()
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
        Dim reader As SqlDataReader = Nothing
        Dim row As New Dictionary(Of String, Object)

        Try
            connection.Open()
            Dim command As New SqlCommand(sql, connection)
            reader = command.ExecuteReader()
            If reader.Read() Then
                For i As Integer = 0 To reader.FieldCount - 1
                    row.Add(reader.GetName(i), reader.GetValue(i))
                Next
            End If
            reader.Close()
            Debug.WriteLine(sql)
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
        Dim reader As SqlDataReader = Nothing
        Dim row As New Dictionary(Of String, Object)

        Try
            connection.Open()
            Dim sql As String = "SELECT " & columns & " FROM " & tableName
            If where <> "" Then
                sql &= " WHERE " & where
            End If
            sql &= " Limit 1"
            Dim command As New SqlCommand(sql, connection)
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

    Public Sub addLogsdb(ByVal tableName As String, Optional ByVal param As Dictionary(Of String, Object) = Nothing, Optional ByVal myaction As String = "")

        Dim result As Integer = 0
        Try
            connection.Open()

            'Dim sql As String = "INSERT INTO " & tableName & " (" & String.Join(",", Data.Keys) & ") VALUES ('" & String.Join("','", Data.Values) & "'); SELECT SCOPE_IDENTITY();"
            Dim sql = "Insert into tbl_audit_partial_log (""reported_by"",""table"",""new_value"",""action"","
            'Dim command As New SqlCommand(sql, connection)
            'result = command.ExecuteNonQuery()
            Dim command As New SqlCommand(sql, connection)
            result = Convert.ToInt32(command.ExecuteScalar())
            Debug.WriteLine(sql)
        Catch ex As Exception
            LogError(ex)
            Debug.WriteLine("Error creating data: " & ex.Message)
        Finally
            connection.Close()
        End Try

    End Sub



    ' Begin a transaction
    Public Sub BeginTransaction()
        Try
            connection.Open()
            mycon = connection.BeginTransaction()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error beginning transaction: " & ex.Message)
        End Try
    End Sub

    ' Commit the transaction
    Public Sub CommitTransaction()
        Try
            mycon.Commit()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error committing transaction: " & ex.Message)
        Finally
            connection.Close()
        End Try
    End Sub

    ' Rollback the transaction
    Public Sub RollbackTransaction()
        Try
            mycon.Rollback()
        Catch ex As Exception
            LogError(ex)
            Console.WriteLine("Error rolling back transaction: " & ex.Message)
        Finally
            connection.Close()
        End Try
    End Sub
End Class
'' Example usage
'Dim sqlInstance As New MsSQL()

'' Start a transaction
'sqlInstance.BeginTransaction()

'Try
'' Perform database operations within the transaction
'Dim data As New Dictionary(Of String, String)
'data.Add("Column1", "Value1")
'data.Add("Column2", "Value2")
'Dim newId As Integer = sqlInstance.Create("TableName", data)

'' If everything is successful, commit the transaction
'sqlInstance.CommitTransaction()

'Catch ex As Exception
'' If an error occurs, rollback the transaction
'sqlInstance.RollbackTransaction()
'End Try
