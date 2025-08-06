<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmPreweighRunList
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
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
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.dgRunNo = New System.Windows.Forms.DataGridView()
        Me.RunNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.FormulaID = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchSize = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Batch = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchList = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.dgRunNo, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'dgRunNo
        '
        Me.dgRunNo.AllowUserToAddRows = False
        Me.dgRunNo.AllowUserToDeleteRows = False
        Me.dgRunNo.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgRunNo.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.RunNo, Me.FormulaID, Me.BatchSize, Me.Batch, Me.BatchList})
        Me.dgRunNo.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgRunNo.Location = New System.Drawing.Point(0, 0)
        Me.dgRunNo.Margin = New System.Windows.Forms.Padding(8, 9, 8, 9)
        Me.dgRunNo.Name = "dgRunNo"
        Me.dgRunNo.ReadOnly = True
        Me.dgRunNo.RowTemplate.Height = 30
        Me.dgRunNo.Size = New System.Drawing.Size(1361, 682)
        Me.dgRunNo.TabIndex = 1
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
        'frmPreweighRunList
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(19.0!, 37.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.AutoSize = True
        Me.ClientSize = New System.Drawing.Size(1361, 682)
        Me.Controls.Add(Me.dgRunNo)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(10, 9, 10, 9)
        Me.Name = "frmPreweighRunList"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Run List"
        Me.TopMost = True
        CType(Me.dgRunNo, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents dgRunNo As DataGridView
    Friend WithEvents RunNo As DataGridViewTextBoxColumn
    Friend WithEvents FormulaID As DataGridViewTextBoxColumn
    Friend WithEvents BatchSize As DataGridViewTextBoxColumn
    Friend WithEvents Batch As DataGridViewTextBoxColumn
    Friend WithEvents BatchList As DataGridViewTextBoxColumn
End Class
