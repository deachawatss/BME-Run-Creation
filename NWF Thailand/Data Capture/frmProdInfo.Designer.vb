<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmProdInfo
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
        Me.PictureBox2 = New System.Windows.Forms.PictureBox()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.lblTL = New System.Windows.Forms.Label()
        Me.cmbSup = New System.Windows.Forms.ComboBox()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.lblTM8 = New System.Windows.Forms.Label()
        Me.lblTM7 = New System.Windows.Forms.Label()
        Me.lblTM6 = New System.Windows.Forms.Label()
        Me.lblTM5 = New System.Windows.Forms.Label()
        Me.lblTM4 = New System.Windows.Forms.Label()
        Me.lblTM3 = New System.Windows.Forms.Label()
        Me.lblTM2 = New System.Windows.Forms.Label()
        Me.lblTM1 = New System.Windows.Forms.Label()
        Me.Label12 = New System.Windows.Forms.Label()
        Me.Label13 = New System.Windows.Forms.Label()
        Me.Label14 = New System.Windows.Forms.Label()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.txtDate = New System.Windows.Forms.DateTimePicker()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.cmbShift = New System.Windows.Forms.ComboBox()
        Me.txtEnd = New System.Windows.Forms.TextBox()
        Me.txtStart = New System.Windows.Forms.TextBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.btnStartShift = New System.Windows.Forms.Button()
        Me.btnEndShift = New System.Windows.Forms.Button()
        Me.Button4 = New System.Windows.Forms.Button()
        Me.Button5 = New System.Windows.Forms.Button()
        CType(Me.PictureBox2, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox2.SuspendLayout()
        Me.GroupBox1.SuspendLayout()
        Me.SuspendLayout()
        '
        'PictureBox2
        '
        Me.PictureBox2.Image = Global.Data_Capture.My.Resources.Resources.Data_Capture_splash
        Me.PictureBox2.Location = New System.Drawing.Point(12, 12)
        Me.PictureBox2.Name = "PictureBox2"
        Me.PictureBox2.Size = New System.Drawing.Size(171, 257)
        Me.PictureBox2.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage
        Me.PictureBox2.TabIndex = 1
        Me.PictureBox2.TabStop = False
        '
        'GroupBox2
        '
        Me.GroupBox2.AutoSize = True
        Me.GroupBox2.Controls.Add(Me.lblTL)
        Me.GroupBox2.Controls.Add(Me.cmbSup)
        Me.GroupBox2.Controls.Add(Me.Button1)
        Me.GroupBox2.Controls.Add(Me.lblTM8)
        Me.GroupBox2.Controls.Add(Me.lblTM7)
        Me.GroupBox2.Controls.Add(Me.lblTM6)
        Me.GroupBox2.Controls.Add(Me.lblTM5)
        Me.GroupBox2.Controls.Add(Me.lblTM4)
        Me.GroupBox2.Controls.Add(Me.lblTM3)
        Me.GroupBox2.Controls.Add(Me.lblTM2)
        Me.GroupBox2.Controls.Add(Me.lblTM1)
        Me.GroupBox2.Controls.Add(Me.Label12)
        Me.GroupBox2.Controls.Add(Me.Label13)
        Me.GroupBox2.Controls.Add(Me.Label14)
        Me.GroupBox2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.GroupBox2.Location = New System.Drawing.Point(208, 306)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(767, 424)
        Me.GroupBox2.TabIndex = 13
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Personnel Information:"
        '
        'lblTL
        '
        Me.lblTL.AutoSize = True
        Me.lblTL.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTL.Location = New System.Drawing.Point(289, 108)
        Me.lblTL.Name = "lblTL"
        Me.lblTL.Size = New System.Drawing.Size(36, 37)
        Me.lblTL.TabIndex = 28
        Me.lblTL.Text = "0"
        '
        'cmbSup
        '
        Me.cmbSup.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbSup.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmbSup.FormattingEnabled = True
        Me.cmbSup.Items.AddRange(New Object() {"", "MARCEL PADEROGAO", "RICHARD PATRICIO", "JOAN CABINTOY"})
        Me.cmbSup.Location = New System.Drawing.Point(296, 55)
        Me.cmbSup.Name = "cmbSup"
        Me.cmbSup.Size = New System.Drawing.Size(458, 45)
        Me.cmbSup.TabIndex = 9
        '
        'Button1
        '
        Me.Button1.Font = New System.Drawing.Font("Microsoft Sans Serif", 19.0!, System.Drawing.FontStyle.Bold)
        Me.Button1.Location = New System.Drawing.Point(296, 147)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(192, 47)
        Me.Button1.TabIndex = 27
        Me.Button1.Text = "Search"
        Me.Button1.UseVisualStyleBackColor = True
        '
        'lblTM8
        '
        Me.lblTM8.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM8.Location = New System.Drawing.Point(429, 347)
        Me.lblTM8.Name = "lblTM8"
        Me.lblTM8.Size = New System.Drawing.Size(332, 37)
        Me.lblTM8.TabIndex = 26
        Me.lblTM8.Text = "0"
        '
        'lblTM7
        '
        Me.lblTM7.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM7.Location = New System.Drawing.Point(429, 300)
        Me.lblTM7.Name = "lblTM7"
        Me.lblTM7.Size = New System.Drawing.Size(332, 37)
        Me.lblTM7.TabIndex = 25
        Me.lblTM7.Text = "0"
        '
        'lblTM6
        '
        Me.lblTM6.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM6.Location = New System.Drawing.Point(429, 253)
        Me.lblTM6.Name = "lblTM6"
        Me.lblTM6.Size = New System.Drawing.Size(332, 37)
        Me.lblTM6.TabIndex = 24
        Me.lblTM6.Text = "0"
        '
        'lblTM5
        '
        Me.lblTM5.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM5.Location = New System.Drawing.Point(429, 206)
        Me.lblTM5.Name = "lblTM5"
        Me.lblTM5.Size = New System.Drawing.Size(332, 37)
        Me.lblTM5.TabIndex = 23
        Me.lblTM5.Text = "0"
        '
        'lblTM4
        '
        Me.lblTM4.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM4.Location = New System.Drawing.Point(45, 347)
        Me.lblTM4.Name = "lblTM4"
        Me.lblTM4.Size = New System.Drawing.Size(332, 37)
        Me.lblTM4.TabIndex = 22
        Me.lblTM4.Text = "0"
        '
        'lblTM3
        '
        Me.lblTM3.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM3.Location = New System.Drawing.Point(45, 300)
        Me.lblTM3.Name = "lblTM3"
        Me.lblTM3.Size = New System.Drawing.Size(332, 37)
        Me.lblTM3.TabIndex = 21
        Me.lblTM3.Text = "0"
        '
        'lblTM2
        '
        Me.lblTM2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM2.Location = New System.Drawing.Point(45, 253)
        Me.lblTM2.Name = "lblTM2"
        Me.lblTM2.Size = New System.Drawing.Size(332, 37)
        Me.lblTM2.TabIndex = 20
        Me.lblTM2.Text = "0"
        '
        'lblTM1
        '
        Me.lblTM1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTM1.Location = New System.Drawing.Point(45, 206)
        Me.lblTM1.Name = "lblTM1"
        Me.lblTM1.Size = New System.Drawing.Size(332, 37)
        Me.lblTM1.TabIndex = 10
        Me.lblTM1.Text = "0"
        '
        'Label12
        '
        Me.Label12.AutoSize = True
        Me.Label12.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label12.Location = New System.Drawing.Point(51, 157)
        Me.Label12.Name = "Label12"
        Me.Label12.Size = New System.Drawing.Size(245, 37)
        Me.Label12.TabIndex = 5
        Me.Label12.Text = "Team Member:"
        '
        'Label13
        '
        Me.Label13.AutoSize = True
        Me.Label13.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label13.Location = New System.Drawing.Point(51, 108)
        Me.Label13.Name = "Label13"
        Me.Label13.Size = New System.Drawing.Size(228, 37)
        Me.Label13.TabIndex = 4
        Me.Label13.Text = "Team Leader:"
        '
        'Label14
        '
        Me.Label14.AutoSize = True
        Me.Label14.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label14.Location = New System.Drawing.Point(51, 58)
        Me.Label14.Name = "Label14"
        Me.Label14.Size = New System.Drawing.Size(189, 37)
        Me.Label14.TabIndex = 3
        Me.Label14.Text = "Supervisor:"
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.txtDate)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.cmbShift)
        Me.GroupBox1.Controls.Add(Me.txtEnd)
        Me.GroupBox1.Controls.Add(Me.txtStart)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.GroupBox1.Location = New System.Drawing.Point(208, 26)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(767, 273)
        Me.GroupBox1.TabIndex = 12
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Shift Information:"
        '
        'txtDate
        '
        Me.txtDate.Format = System.Windows.Forms.DateTimePickerFormat.[Short]
        Me.txtDate.Location = New System.Drawing.Point(242, 46)
        Me.txtDate.Name = "txtDate"
        Me.txtDate.Size = New System.Drawing.Size(326, 44)
        Me.txtDate.TabIndex = 10
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.Location = New System.Drawing.Point(51, 52)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(98, 37)
        Me.Label1.TabIndex = 9
        Me.Label1.Text = "Date:"
        '
        'cmbShift
        '
        Me.cmbShift.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbShift.FormattingEnabled = True
        Me.cmbShift.Items.AddRange(New Object() {"", "First Shift", "Second Shift", "Third Shift", "Fourth Shift"})
        Me.cmbShift.Location = New System.Drawing.Point(242, 97)
        Me.cmbShift.Name = "cmbShift"
        Me.cmbShift.Size = New System.Drawing.Size(326, 45)
        Me.cmbShift.TabIndex = 8
        '
        'txtEnd
        '
        Me.txtEnd.Location = New System.Drawing.Point(242, 202)
        Me.txtEnd.Name = "txtEnd"
        Me.txtEnd.Size = New System.Drawing.Size(326, 44)
        Me.txtEnd.TabIndex = 7
        '
        'txtStart
        '
        Me.txtStart.Location = New System.Drawing.Point(242, 148)
        Me.txtStart.Name = "txtStart"
        Me.txtStart.Size = New System.Drawing.Size(326, 44)
        Me.txtStart.TabIndex = 6
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label5.Location = New System.Drawing.Point(51, 205)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(172, 37)
        Me.Label5.TabIndex = 5
        Me.Label5.Text = "End Time:"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.Location = New System.Drawing.Point(51, 151)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(185, 37)
        Me.Label3.TabIndex = 4
        Me.Label3.Text = "Start Time:"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.Location = New System.Drawing.Point(51, 100)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(96, 37)
        Me.Label4.TabIndex = 3
        Me.Label4.Text = "Shift:"
        '
        'btnStartShift
        '
        Me.btnStartShift.BackColor = System.Drawing.Color.PaleGreen
        Me.btnStartShift.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnStartShift.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.btnStartShift.Location = New System.Drawing.Point(5, 377)
        Me.btnStartShift.Name = "btnStartShift"
        Me.btnStartShift.Size = New System.Drawing.Size(197, 64)
        Me.btnStartShift.TabIndex = 14
        Me.btnStartShift.Text = "&Start Shift"
        Me.btnStartShift.UseVisualStyleBackColor = False
        '
        'btnEndShift
        '
        Me.btnEndShift.BackColor = System.Drawing.Color.LightCoral
        Me.btnEndShift.Enabled = False
        Me.btnEndShift.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnEndShift.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.btnEndShift.Location = New System.Drawing.Point(5, 453)
        Me.btnEndShift.Name = "btnEndShift"
        Me.btnEndShift.Size = New System.Drawing.Size(197, 64)
        Me.btnEndShift.TabIndex = 15
        Me.btnEndShift.Text = "&End Shift"
        Me.btnEndShift.UseVisualStyleBackColor = False
        '
        'Button4
        '
        Me.Button4.BackColor = System.Drawing.Color.PeachPuff
        Me.Button4.Enabled = False
        Me.Button4.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.Button4.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button4.Location = New System.Drawing.Point(5, 529)
        Me.Button4.Name = "Button4"
        Me.Button4.Size = New System.Drawing.Size(197, 64)
        Me.Button4.TabIndex = 16
        Me.Button4.Text = "&View Report"
        Me.Button4.UseVisualStyleBackColor = False
        '
        'Button5
        '
        Me.Button5.BackColor = System.Drawing.Color.Peru
        Me.Button5.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.Button5.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button5.Location = New System.Drawing.Point(5, 608)
        Me.Button5.Name = "Button5"
        Me.Button5.Size = New System.Drawing.Size(197, 64)
        Me.Button5.TabIndex = 17
        Me.Button5.Text = "&Logout"
        Me.Button5.UseVisualStyleBackColor = False
        '
        'frmProdInfo
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(982, 739)
        Me.Controls.Add(Me.Button5)
        Me.Controls.Add(Me.Button4)
        Me.Controls.Add(Me.btnEndShift)
        Me.Controls.Add(Me.btnStartShift)
        Me.Controls.Add(Me.GroupBox2)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.PictureBox2)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None
        Me.Name = "frmProdInfo"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Data Capture"
        CType(Me.PictureBox2, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents PictureBox2 As PictureBox
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents lblTM8 As Label
    Friend WithEvents lblTM7 As Label
    Friend WithEvents lblTM6 As Label
    Friend WithEvents lblTM5 As Label
    Friend WithEvents lblTM4 As Label
    Friend WithEvents lblTM3 As Label
    Friend WithEvents lblTM2 As Label
    Friend WithEvents lblTM1 As Label
    Friend WithEvents Label12 As Label
    Friend WithEvents Label13 As Label
    Friend WithEvents Label14 As Label
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents txtEnd As TextBox
    Friend WithEvents txtStart As TextBox
    Friend WithEvents Label5 As Label
    Friend WithEvents Label3 As Label
    Friend WithEvents Label4 As Label
    Friend WithEvents Button1 As Button
    Friend WithEvents cmbShift As ComboBox
    Friend WithEvents btnStartShift As Button
    Friend WithEvents btnEndShift As Button
    Friend WithEvents Button4 As Button
    Friend WithEvents Button5 As Button
    Friend WithEvents lblTL As Label
    Friend WithEvents cmbSup As ComboBox
    Friend WithEvents Label1 As Label
    Friend WithEvents txtDate As DateTimePicker
End Class
