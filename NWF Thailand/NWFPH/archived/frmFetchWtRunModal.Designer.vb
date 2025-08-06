<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class frmFetchWtRunModal
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.DataGridView1 = New System.Windows.Forms.DataGridView()
        Me.RunNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.FormulaID = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchSize = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Batch = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchList = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'DataGridView1
        '
        Me.DataGridView1.AllowUserToAddRows = False
        Me.DataGridView1.AllowUserToDeleteRows = False
        Me.DataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.DataGridView1.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.RunNo, Me.FormulaID, Me.BatchSize, Me.Batch, Me.BatchList})
        Me.DataGridView1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.DataGridView1.Location = New System.Drawing.Point(0, 0)
        Me.DataGridView1.Margin = New System.Windows.Forms.Padding(8, 9, 8, 9)
        Me.DataGridView1.Name = "DataGridView1"
        Me.DataGridView1.ReadOnly = True
        Me.DataGridView1.RowTemplate.Height = 30
        Me.DataGridView1.Size = New System.Drawing.Size(1361, 682)
        Me.DataGridView1.TabIndex = 1
        '
        'RunNo
        '
        Me.RunNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.RunNo.HeaderText = "Run No"
        Me.RunNo.Name = "RunNo"
        Me.RunNo.ReadOnly = True
        '
        'FormulaID
        '
        Me.FormulaID.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.FormulaID.HeaderText = "Formula ID"
        Me.FormulaID.Name = "FormulaID"
        Me.FormulaID.ReadOnly = True
        '
        'BatchSize
        '
        Me.BatchSize.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.BatchSize.HeaderText = "Batch Size"
        Me.BatchSize.Name = "BatchSize"
        Me.BatchSize.ReadOnly = True
        '
        'Batch
        '
        Me.Batch.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Batch.HeaderText = "Batch"
        Me.Batch.Name = "Batch"
        Me.Batch.ReadOnly = True
        '
        'BatchList
        '
        Me.BatchList.HeaderText = "Batchlist"
        Me.BatchList.Name = "BatchList"
        Me.BatchList.ReadOnly = True
        Me.BatchList.Visible = False
        '
        'frmFetchWtRunModal
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(19.0!, 37.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(1361, 682)
        Me.Controls.Add(Me.DataGridView1)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(6, 6, 6, 6)
        Me.Name = "frmFetchWtRunModal"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Run No"
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents DataGridView1 As DataGridView
    Friend WithEvents RunNo As DataGridViewTextBoxColumn
    Friend WithEvents FormulaID As DataGridViewTextBoxColumn
    Friend WithEvents BatchSize As DataGridViewTextBoxColumn
    Friend WithEvents Batch As DataGridViewTextBoxColumn
    Friend WithEvents BatchList As DataGridViewTextBoxColumn
End Class
